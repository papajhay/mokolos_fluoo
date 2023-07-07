<?php
declare(strict_types=1);
namespace App\Service\Provider\Adesa;

use App\Entity\TSupplierOrder;
use App\Repository\TSupplierOrderRepository;
use App\Service\Provider\SupplierOrderService;

class ManageMail extends BaseAdesa
{
    public function __construct(
        private AchattodbEmail $achattodbEmail,
        private TLockProcess $lockProcess,
        private TSupplierOrderRepository $TSupplierOrderRepository,
        private SupplierOrderService $supplierOrderService
    ){}

    /**
     *  cette fonction traite les mails qui arrive sur la boite achat
     * @param AchattodbEmail $achattodbEmail objet de notre mail à traiter
     * @param TLockProcess $lockProcess
     */
       public function manageMail()
       {
            $matchesSubject	 = array();
            $matchesBody	 = array();

            $this->lockProcess->updateStage('Traitement de l\'email ' . $this->achattodbEmail->getId() . ' de ' . $this->getName());

            // on récupére le log du lockprocess
            //$this->setLog($lockProcess->getLog());

            // traitement email de fichier reçu
            if(preg_match($this->_pcreMailOrderConfirmationBody(), $this->achattodbEmail->getMessageHtml(), $matchesBody))
            {
                $this->lockProcess->updateStage('Traitement de l\'email ' . $this->achattodbEmail->getId() . ' de confirmation de commande ' . $this->getName() . ' pour la commande fournisseur ' . $matchesBody[1]);

                // traitement du mail
                return $this->_manageMailOrderConfirmation($this->achattodbEmail, $matchesBody[1], $matchesBody[2]);
            }

            // traitement email de BAT validé
            if(preg_match($this->_pcreMailProofOk(), $this->achattodbEmail->getSubject(), $matchesSubject))
            {
                $this->lockProcess->updateStage('Traitement de l\'email ' . $this->achattodbEmail->getId() . ' de BAT validé ' . $this->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

                // traitement du mail
                return $this->_manageMailProofOk($this->achattodbEmail, $matchesSubject[1]);
            }

            // traitement email de BAT refusé
            if(preg_match($this->_pcreMailProofRefused(), $this->achattodbEmail->getSubject(), $matchesSubject))
            {
                $this->lockProcess->updateStage('Traitement de l\'email ' . $this->achattodbEmail->getId() . ' de BAT refusé ' . $this->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

                // traitement du mail
                return $this->_manageMailProofRefused($this->achattodbEmail, $matchesSubject[1]);
            }

            // traitement email d'expedition
            if(preg_match($this->_pcreMailShippingSubject(), $this->achattodbEmail->getSubject()) && preg_match($this->_pcreMailShippingBody(), $this->achattodbEmail->getMessage(), $matchesBody))
            {
                $this->lockProcess->updateStage('Traitement de l\'email ' . $this->achattodbEmail->getId() . ' d\'expédition ' . $this->getName() . ' pour la commande fournisseur ' . $matchesBody[1]);

                // traitement du mail
                return $this->_manageMailExpedition($this->achattodbEmail, $matchesBody[1]);
            }

            // traitement mail de facture
            if(preg_match($this->_pcreMailInvoiceSubject(), $this->achattodbEmail->getSubject(), $matchesSubject))
            {
                $this->lockProcess->updateStage('Traitement de l\'email ' . $this->achattodbEmail->getId() . ' de facture ' . $this->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

                // on traite le mail
                return $this->_manageMailInvoice($this->achattodbEmail, $matchesSubject[1]);
            }

            // mail de devis
            if(preg_match($this->_pcreMailQuote(), $this->achattodbEmail->getSubject()) || preg_match($this->_pcreMailQuoteAmount(), $this->achattodbEmail->getSubject()))
            {
                $this->lockProcess->updateStage('Traitement de l\'email ' . $this->achattodbEmail->getId() . ' de devis ' . $this->getName() . '.');

                // on passe juste le mail en traité
                $this->achattodbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED)
                    ->save();

                // on quitte la fonction
                return true;
            }

            // on ajoute un log d'erreur
//            $this->lockProcess->getLog()->Erreur('mail ' . $this->getName() . ' de type inconnu.');
//            $this->lockProcess->getLog()->Erreur('id : ' . $this->achattodbEmail->getId());

            return false;
       }



    /**
     * traite un email d'expédition
     * @param int $supplierOrderId numéro de la commande chez le fournisseur
     * @return boolean true si on a réussi à récupérer les infos et false si on a un probléme
     */
    private function _manageMailExpedition($supplierOrderId)
    {
        $matches	 = array();
        $matchesUrl	 = array();

        //Notre tableau qui contient type du colis, les numero command et l'url
        $atexteColis = array();

        // on recherche la commande fournisseur
        $supplierOrder = $this->TSupplierOrderRepository->findBySupplierId($supplierOrderId, $this->getId());

        // si on n'a pas trouvé la commande fournisseur
        if($supplierOrder == null)
        {
            // on ajoute un message d'erreur
            //$this->getLog()->Erreur('Commande founrisseur ' . $this->getName() . ' "' . $supplierOrderId . '" non trouvée.');

            return false;
        }

        // mise à jour du statut si besoin
        $this->supplierOrderService->updateStatusIfAfterCurrent($supplierOrder, TSupplierOrderStatus::ID_STATUS_DISPATCHED);

        // on récupére les commandes qui sont lié à notre commande fournisseur
        $aOrderSupplierOrder = $supplierOrder->getAOrderSupplierOrder();

        // rien ne correspond
        if(count($aOrderSupplierOrder) == 0)
        {
            // on ajoute un message d'erreur
//            $this->getLog()->Erreur('Aucune commande lié à la commande fournisseur.');
//            $this->getLog()->Erreur(var_export($supplierOrder, true));

            // on quitte la fonction
            return false;
        }

        // Si on ne trouve pas les infos de colis dans le mail
        if(!preg_match($this->_pcreMailShippingDetail(), $this->achattodbEmail->getMessage(), $matches))
        {
            // on renvoi une erreur
//            $this->getLog()->Erreur('Info d\'expédition non trouvé.');
            return false;
        }

        // si on n'arrive pas à extraire les url
        if(!preg_match_all($this->_pcreUrlFromHref(), $matches[2], $matchesUrl))
        {
            // on renvoi une erreur
//            $this->getLog()->Erreur('Url de tracking non trouvé.');
            return false;
        }

        // création du texte du colis :
        $atexteColis['numColis'] = explode(', ', $matches[1]);
        $atexteColis['urlColis'] = $matchesUrl[1];

        foreach(explode(', ', $matches[3]) as $carrierName)
        {
            // suivant le transporteur
            switch(trim($carrierName))
            {
                case 'Chronopost':
                    $idTransporteur = TTransporteur::ID_TRANSPORTEUR_CHRONOPOST;
                    break;

                case 'La Poste':
                    $idTransporteur = TTransporteur::ID_CARRIER_COLISSIMO;
                    break;

                case 'TNT':
                    $idTransporteur = TTransporteur::ID_TRANSPORTEUR_TNT;
                    break;

                default:
                    // on renvoi une erreur
//                    $this->getLog()->Erreur('Transporteur inconnu "' . $matches[3] . '".');
                    return false;
            }

            // récupération du transporteur
            $carrier = TTransporteur::findByIdWithChildObject($idTransporteur);

            // ajout d'info au log
            $this->getLog()->addLogContent('Livré par ' . $carrier->getNameComplet());

            // création du texte du colis :
            $atexteColis['idTransporteur']	 = $carrier->getId();
            $atexteColis['transporteur']	 = $carrier->getNameComplet();
        }

        // si il manque un élément de tracking
        if(count($atexteColis['numColis']) != count($atexteColis['urlColis']))
        {
            // on renvoi une erreur
//            $this->getLog()->Erreur('Probléme avec le tracking du colis.');
//            $this->getLog()->Erreur(var_export($atexteColis, true));

            // dans ce cas on supprime toutes les info de colis
            $atexteColis = array();
        }

        // pour chaque commande correspondant à notre job
        foreach($aOrderSupplierOrder as $orderSupplierOrder)
        {
            // on change le statut de la commande et on envoi l'email
            $orderSupplierOrder->getOrder()->setAsLivraison($this->getName(), $atexteColis);
        }

        // passage du mail en traité
        $this->achattodbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED)
            ->save();

        return true;
    }

    /**
     * gestion des mail de facture
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @return bool true en cas de succés et false en cas de probléme
     */
    protected function _manageMailInvoice($supplierOrderId)
    {
        // on met à jour la commande fournisseur ou la créé au besoin. On passe la commande en exépdié au cas ou elle n'y soit pas déjà
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_DISPATCHED, $this->achattodbEmail, null, null, null, null, $this->getName() . ' a généré une facture.');
    }

}