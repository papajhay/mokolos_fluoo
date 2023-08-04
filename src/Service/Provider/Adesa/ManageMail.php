<?php
declare(strict_types=1);

namespace App\Service\Provider\Adesa;

use App\Entity\AchattodbEmail;
use App\Entity\Provider;
use App\Entity\TLockProcess;
use App\Repository\TSupplierOrderRepository;
use App\Service\Provider\SupplierOrderService;
use Doctrine\ORM\EntityManagerInterface;

class ManageMail extends BaseAdesa
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TSupplierOrderRepository $TSupplierOrderRepository,
        private SupplierOrderService $supplierOrderService
    ) {
    }

    /**
     *  cette fonction traite les mails qui arrive sur la boite achat.
     * @param AchattodbEmail $achattodbEmail objet de notre mail à traiter
     */
    public function manageMail(AchattodbEmail $achattodbEmail, TLockProcess $lockProcess, Provider $provider): bool
    {
        $matchesSubject = [];
        $matchesBody = [];

        $lockProcess->updateStage('Traitement de l\'email '.$achattodbEmail->getId().' de '.$provider > getName());

        // on récupére le log du lockprocess
        // $this->setLog($lockProcess->getLog());

        // traitement email de fichier reçu
        if (preg_match($this->_pcreMailOrderConfirmationBody(), $achattodbEmail->getMessageHtml(), $matchesBody)) {
            $lockProcess->updateStage('Traitement de l\'email '.$achattodbEmail->getId().' de confirmation de commande '.$provider->getName().' pour la commande fournisseur '.$matchesBody[1]);

            // traitement du mail
            return $this->_manageMailOrderConfirmation($achattodbEmail, $matchesBody[1], $matchesBody[2]);
        }

        // traitement email de BAT validé
        if (preg_match($this->_pcreMailProofOk(), $achattodbEmail->getSubject(), $matchesSubject)) {
            $lockProcess->updateStage('Traitement de l\'email '.$achattodbEmail->getId().' de BAT validé '.$provider->getName().' pour la commande fournisseur '.$matchesSubject[1]);

            // traitement du mail
            return $this->_manageMailProofOk($achattodbEmail, $matchesSubject[1]);
        }

        // traitement email de BAT refusé
        if (preg_match($this->_pcreMailProofRefused(), $achattodbEmail->getSubject(), $matchesSubject)) {
            $lockProcess->updateStage('Traitement de l\'email '.$achattodbEmail->getId().' de BAT refusé '.$provider->getName().' pour la commande fournisseur '.$matchesSubject[1]);

            // traitement du mail
            return $this->_manageMailProofRefused($achattodbEmail, $matchesSubject[1]);
        }

        // traitement email d'expedition
        if (preg_match($this->_pcreMailShippingSubject(), $achattodbEmail->getSubject()) && preg_match($this->_pcreMailShippingBody(), $achattodbEmail->getMessage(), $matchesBody)) {
            $lockProcess->updateStage('Traitement de l\'email '.$achattodbEmail->getId().' d\'expédition '.$provider->getName().' pour la commande fournisseur '.$matchesBody[1]);

            // traitement du mail
            return $this->_manageMailExpedition($achattodbEmail, $matchesBody[1]);
        }

        // traitement mail de facture
        if (preg_match($this->_pcreMailInvoiceSubject(), $achattodbEmail->getSubject(), $matchesSubject)) {
            $lockProcess->updateStage('Traitement de l\'email '.$achattodbEmail->getId().' de facture '.$provider->getName().' pour la commande fournisseur '.$matchesSubject[1]);

            // on traite le mail
            return $this->_manageMailInvoice($achattodbEmail, $matchesSubject[1], $provider);
        }

        // mail de devis
        if (preg_match($this->_pcreMailQuote(), $achattodbEmail->getSubject()) || preg_match($this->_pcreMailQuoteAmount(), $achattodbEmail->getSubject())) {
            $lockProcess->updateStage('Traitement de l\'email '.$achattodbEmail->getId().' de devis '.$provider->getName().'.');

            // on passe juste le mail en traité
            $achattodbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED);
            $this->entityManager->persist($achattodbEmail);
            $this->entityManager->flush();

            // on quitte la fonction
            return true;
        }

        // on ajoute un log d'erreur
        //            $this->lockProcess->getLog()->Erreur('mail ' . $this->getName() . ' de type inconnu.');
        //            $this->lockProcess->getLog()->Erreur('id : ' . $this->achattodbEmail->getId());

        return false;
    }

    /**
     * traite un email d'expédition.
     * @param  int  $supplierOrderId numéro de la commande chez le fournisseur
     * @return bool true si on a réussi à récupérer les infos et false si on a un probléme
     */
    private function _manageMailExpedition(AchattodbEmail $achattodbEmail, int $supplierOrderId, Provider $provider): bool
    {
        $matches = [];
        $matchesUrl = [];

        // Notre tableau qui contient type du colis, les numero command et l'url
        $atexteColis = [];

        // on recherche la commande fournisseur
        $supplierOrder = $this->TSupplierOrderRepository->findBySupplierId($supplierOrderId, $provider->getId());

        // si on n'a pas trouvé la commande fournisseur
        if (null === $supplierOrder) {
            // on ajoute un message d'erreur
            // $this->getLog()->Erreur('Commande founrisseur ' . $this->getName() . ' "' . $supplierOrderId . '" non trouvée.');

            return false;
        }

        // mise à jour du statut si besoin
        $this->supplierOrderService->updateStatusIfAfterCurrent($supplierOrder, TSupplierOrderStatus::ID_STATUS_DISPATCHED);

        // on récupére les commandes qui sont lié à notre commande fournisseur
        $aOrderSupplierOrder = $supplierOrder->getAOrderSupplierOrder();

        // rien ne correspond
        if (0 === count($aOrderSupplierOrder)) {
            // on ajoute un message d'erreur
            //            $this->getLog()->Erreur('Aucune commande lié à la commande fournisseur.');
            //            $this->getLog()->Erreur(var_export($supplierOrder, true));

            // on quitte la fonction
            return false;
        }

        // Si on ne trouve pas les infos de colis dans le mail
        if (!preg_match($this->_pcreMailShippingDetail(), $achattodbEmail->getMessage(), $matches)) {
            // on renvoi une erreur
            //            $this->getLog()->Erreur('Info d\'expédition non trouvé.');
            return false;
        }

        // si on n'arrive pas à extraire les url
        if (!preg_match_all($this->_pcreUrlFromHref(), $matches[2], $matchesUrl)) {
            // on renvoi une erreur
            //            $this->getLog()->Erreur('Url de tracking non trouvé.');
            return false;
        }

        // création du texte du colis :
        $atexteColis['numColis'] = explode(', ', $matches[1]);
        $atexteColis['urlColis'] = $matchesUrl[1];

        foreach (explode(', ', $matches[3]) as $carrierName) {
            // suivant le transporteur
            switch (trim($carrierName)) {
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

            // TODO service TTransporter
            // récupération du transporteur
            $carrier = TTransporteur::findByIdWithChildObject($idTransporteur);

            // ajout d'info au log
            // $this->getLog()->addLogContent('Livré par ' . $carrier->getNameComplet());

            // création du texte du colis :
            $atexteColis['idTransporteur'] = $carrier->getId();
            $atexteColis['transporteur'] = $carrier->getNameComplet();
        }

        // si il manque un élément de tracking
        if (count($atexteColis['numColis']) !== count($atexteColis['urlColis'])) {
            // on renvoi une erreur
            //            $this->getLog()->Erreur('Probléme avec le tracking du colis.');
            //            $this->getLog()->Erreur(var_export($atexteColis, true));

            // dans ce cas on supprime toutes les info de colis
            $atexteColis = [];
        }

        // pour chaque commande correspondant à notre job
        foreach ($aOrderSupplierOrder as $orderSupplierOrder) {
            // TODO Service Order
            // on change le statut de la commande et on envoi l'email
            $orderSupplierOrder->getOrder()->setAsLivraison($provider->getName(), $atexteColis);
        }

        // passage du mail en traité
        $achattodbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED);
        $this->entityManager->persist($achattodbEmail);
        $this->entityManager->flush();

        return true;
    }

    /**
     * gestion des mail de facture.
     * @param  int  $supplierOrderId numéro de la commande chez le fournisseur
     * @return bool true en cas de succés et false en cas de probléme
     */
    protected function _manageMailInvoice(AchattodbEmail $achattodbEmail, int $supplierOrderId, Provider $provider): bool
    {
        // on met à jour la commande fournisseur ou la créé au besoin. On passe la commande en exépdié au cas ou elle n'y soit pas déjà
        return $this->supplierOrderService->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_DISPATCHED, $achattodbEmail, null, null, null, null, $provider->getName().' a généré une facture.');
    }

    /**
     * Traitement du mail de confirmation de commande.
     * @param  AchattodbEmail $achattodbEmail  Objet AchattodbEmail
     * @param  int            $supplierOrderId numéro de la commande chez le fournisseur
     * @param  int            $orderIdRaw      numéro de la commande ou des commandes
     * @return bool           true si tout se passe bien et false en cas de probléme
     */
    private function _manageMailOrderConfirmation(AchattodbEmail $achattodbEmail, int $supplierOrderId, int $orderIdRaw): bool
    {
        // on recherche la commande fournisseur ou la créé au besoin
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_PRODUCTION, $achattodbEmail, $this->multipleOrderNumberStringToArray($orderIdRaw));
    }

    /**
     * Traitement du mail de BAT validé.
     * @param  AchattodbEmail $achattodbEmail  Objet AchattodbEmail
     * @param  int            $supplierOrderId numéro de la commande chez le fournisseur
     * @return bool           true si tout se passe bien et false en cas de probléme
     */
    private function _manageMailProofOk(AchattodbEmail $achattodbEmail, int $supplierOrderId): bool
    {
        // on recherche la commande fournisseur ou la créé au besoin
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_PRODUCTION, $achattodbEmail, null, null, null, null, 'BAT Validé');
    }

    /**
     * Traitement du mail de BAT refusé.
     * @param  AchattodbEmail $achattodbEmail  Objet AchattodbEmail
     * @param  int            $supplierOrderId numéro de la commande chez le fournisseur
     * @return bool           true si tout se passe bien et false en cas de probléme
     */
    private function _manageMailProofRefused(AchattodbEmail $achattodbEmail, int $supplierOrderId): bool
    {
        // on recherche la commande fournisseur ou la créé au besoin
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_ERROR, $achattodbEmail, null, null, null, null, 'BAT Refusé par '.$this->provider->getName(), OrdersStatus::STATUS_DEPART_FAB_RETOUR);
    }
}
