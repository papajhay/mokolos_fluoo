<?php

namespace App\Service\Provider\RealizedPrint;

use App\Entity\Provider;
use App\Entity\Ttransporter;
use App\Service\Provider\OrderSupplierOrderService;
use App\Service\Provider\SupplierOrderService;

class ManageMail extends BaseRealizedPrint
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SupplierOrderService $supplierOrderService,
        private OrderSupplierOrderService $orderSupplierOrderService

    ) {
    }
    /**traitement d'un email de fichier recu
     * @param AchattodbEmail $achattodbEmail le mail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @param int $orderId l'id de la commande chez nous
     * @return bool true en cas de succés et false en cas de probléme
     */
    private function _manageMailFileReceived(AchattodbEmail $achattodbEmail,string $supplierOrderId, int $orderId): bool
    {
        // on met à jour la commande fournisseur ou la créé au besoin
        // TODO service updateOrderSupplier
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_FILE_RECEIVED, $achattodbEmail, $orderId);
    }

    /**
     * traitement d'un email de passage en production
     * @param AchattodbEmail $achattodbEmail le mail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @param int $orderId l'id de la commande chez nous
     * @param string $dispatchDateRaw la date d'expédition
     * @return bool true en cas de succés et false en cas de probléme
     * @throws \Exception
     */
    private function _manageMailOrderInProduction(AchattodbEmail $achattodbEmail, string $supplierOrderId, int $orderId, string $dispatchDateRaw): bool
    {
        // calcul de la date d'expédition
        $dispatchDate = new \DateTimeImmutable($dispatchDateRaw);

        // calcul de la date de livraison
        //$deliveryDate = DateHeure::jPlusX($this->NB_JOUR_LIVRAISON, $dispatchDate);
        $deliveryDate = $dispatchDate->modify('+'.$this->NB_JOUR_LIVRAISON.' days');

        // on met à jour la commande fournisseur ou la créé au besoin
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_PRODUCTION, $achattodbEmail, $orderId, $deliveryDate);
    }

    /**
     * traitement d'un email de commande en expedition
     * @param AchattodbEmail $achattodbEmail le mail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @param int|null $orderId [=null] l'id de la commande chez nous ou null si non disponible
     * @return bool true en cas de succés et false en cas de probléme
     */
    private function _manageMailDispatched(AchattodbEmail $achattodbEmail, string $supplierOrderId,int $orderId = null): bool
    {
        $comments = '';

        // pr défaut aucune information de livraison
        $aDeliveryInformation = null;

        // récupértion des informations sur la commande dans l'API
        $json = $this->_apiGetOrder($supplierOrderId);

        // si on a un soucis avec l'appel du json
        if(!$json || !isset($json['tracking']) || !isset($json['tracking']['sent']) || !isset($json['tracking']['sent_date']) || !isset($json['tracking']['carrier']) || !isset($json['tracking']['tracking_numbers']) || !isset($json['tracking']['tracking_url']))
        {
            // on ajoute un commentaire
            $comments = 'Impossible de récupérer les informations de tracking pour cette commande. Elle n\'a probablement pas été créé via l\'API chez '. $this->getNomFour();
        }
        // si la comande n'a pas encore été expédié
        elseif($json['tracking']['sent'] != true)
        {
            // on ajoute un commentaire
            $comments = 'Aucune information de tracking pour cette commande.';
        }
        // comma expédié
        else
        {
            // suivant le transporteur
            switch($json['tracking']['carrier'])
            {
                case 'GLS':
                    // récupération du transporteur
                    // TODO TTransporter Service
                    $transporter = TTransporter::findByIdWithChildObject(TTransporter::ID_CARRIER_GLS);

                    // ajout d'info au log
                    //$this->getLog()->addLogContent('Livré par ' . $transporteur->getTraNomComplet());

                    // création du texte du colis :
                    $aDeliveryInformation					 = array();
                    $aDeliveryInformation['idTransporter']	 = $transporter->getId();
                    $aDeliveryInformation['transporter']	 = $transporter->getName();
                    $aDeliveryInformation['numColis']		 = $json['tracking']['tracking_numbers'];
                    $aDeliveryInformation['urlColis']		 = $json['tracking']['tracking_url'];
                    $aDeliveryInformation['shippingDate']	 = new \DateTimeImmutable($json['tracking']['sent_date']);

                    break;

                case 'DPD':
                    // récupération du transporteur
                    $transporter = TTransporter::findByIdWithChildObject(TTransporter::ID_CARRIER_DPD_FRANCE);

                    // ajout d'info au log
                    //$this->getLog()->addLogContent('Livré par ' . $transporteur->getTraNomComplet());

                    // création du texte du colis :
                    $aDeliveryInformation					 = array();
                    $aDeliveryInformation['idTransporter']	 = $transporter->getId();
                    $aDeliveryInformation['transporter']	 = $transporter->getName();
                    $aDeliveryInformation['numColis']		 = $json['tracking']['tracking_numbers'];
                    $aDeliveryInformation['urlColis']		 = $json['tracking']['tracking_url'];
                    $aDeliveryInformation['shippingDate']	 = new \DateTimeImmutable($json['tracking']['sent_date']);

                    break;

                case 'France Express':
                    // récupération du transporteur
                    $transporter = TTransporter::findByIdWithChildObject(TTransporter::ID_CARRIER_FRANCE_EXPRESS);

                    // ajout d'info au log
                    //$this->getLog()->addLogContent('Livré par ' . $transporteur->getTraNomComplet());

                    // création du texte du colis :
                    $aDeliveryInformation					 = array();
                    $aDeliveryInformation['idTransporter']	 = $transporter->getId();
                    $aDeliveryInformation['transporter']	 = $transporter->getName();
                    $aDeliveryInformation['numColis']		 = $json['tracking']['tracking_numbers'];
                    $aDeliveryInformation['urlColis']		 = $json['tracking']['tracking_url'];
                    $aDeliveryInformation['shippingDate']	 = new \DateTimeImmutable($json['tracking']['sent_date']);

                    break;

                case 'Ciblex':
                    // récupération du transporteur
                    $transporteur = TTransporter::findByIdWithChildObject(TTransporter::ID_CARRIER_CIBLEX);

                    // ajout d'info au log
                    //$this->getLog()->addLogContent('Livré par ' . $transporteur->getTraNomComplet());

                    // création du texte du colis :
                    $aDeliveryInformation					 = array();
                    $aDeliveryInformation['idTransporter']	 = $transporteur->getId();
                    $aDeliveryInformation['transporter']	 = $transporteur->getName();
                    $aDeliveryInformation['numColis']		 = $json['tracking']['tracking_numbers'];
                    $aDeliveryInformation['urlColis']		 = $json['tracking']['tracking_url'];
                    $aDeliveryInformation['shippingDate']	 = new \DateTimeImmutable($json['tracking']['sent_date']);

                    break;

                // transporteur inconnu
                default:
//                    $this->getLog()->Erreur('Transporteur inconnu.');
//                    $this->getLog()->Erreur(var_export($json, true));

                    break;
            }
        }

        // on met à jour la commande fournisseur ou la créé au besoin
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_DISPATCHED, $achattodbEmail, $orderId, null, null, null, $comments, OrdersStatus::STATUS_EXPEDITION, $aDeliveryInformation);
    }


    /**
     * traitement d'un email de probléme bloquant
     * @param AchattodbEmail $achattodbEmail le mail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @param int $orderId l'id de la commande chez nous
     * @return bool true en cas de succés et false en cas de probléme
     */
    private function _manageMailError(AchattodbEmail $achattodbEmail, string $supplierOrderId, int $orderId): bool
    {
        // on met à jour la commande fournisseur ou la créé au besoin
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_ERROR, $achattodbEmail, $orderId, null, null, null, $achattodbEmail->getMessage(), OrdersStatus::STATUS_DEPART_FAB_RETOUR);
    }

    /**
     * traitement d'un email de réimpression
     * @param AchattodbEmail $achattodbEmail le mail
     * @param string $supplierOrderId numéro de la nouvel commande chez le fournisseur
     * @param int $originalSupplierOrderId l'id de la commande d'orignie chez le fournisseur
     * @param string $dispatchDateRaw la date d'expédition
     * @param float $buyingPriceWithTax [=0] le prix d'achat
     * @return bool true en cas de succés et false en cas de probléme
     */
    private function _manageMailReprint(Provider $provider, AchattodbEmail $achattodbEmail, string $supplierOrderId, int $originalSupplierOrderId,string $dispatchDateRaw, float $buyingPriceWithTax = 0)
    {
        // on cherche la commande fournsiseur d'origine
        $originalSupplierOrder = $this->supplierOrderService->findBySupplierId($originalSupplierOrderId, $provider->getId());

        // si on ne trouve pas la commande fournisseur d'origine
        if(!$originalSupplierOrder->exist())
        {
            // on renvoi une erreur
//            $this->getLog()->Erreur('Commande d\'origine introuvable pour une reimpression');
//            $this->getLog()->Erreur(var_export($supplierOrderId, true));

            // on quitte la fonction
            return false;
        }

        // pour chaque comande lié à notre commande fournisseur
        foreach($originalSupplierOrder->getTaOrderSupplierOrders() as $orderSupplierOrder)
        {
            // on récupére l'id de la commande
            $aIdOrders[] = $orderSupplierOrder->getIdOrder();

            // on passe la commande en retour
            //TODO updateStatus in OrderService and create OrdersStatusHistory
            $orderSupplierOrder->getTOrder()->updateStatus(OrdersStatus::STATUS_DEPART_FAB_RETOUR, 'Une Reimpression a été lancé.', OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, '', '', $this->getNomFour());
        }

        // si la commande d'origine est toujours en production
        if($originalSupplierOrder->getIdSupplierOrderStatus() == TSupplierOrderStatus::ID_STATUS_PRODUCTION)
        {
            // on la passe en statut expédié
            $this->supplierOrderService->updateStatusIfAfterCurrent($originalSupplierOrder,TSupplierOrderStatus::ID_STATUS_DISPATCHED);

        }

        // si on a une date de livraison
        if($dispatchDateRaw != null)
        {
            // calcul de la date d'expédition
            $dispatchDate = new \DateTimeImmutable($dispatchDateRaw);

            // calcul de la date de livraison
            $deliveryDate = $dispatchDate->modify('+'.$this->NB_JOUR_LIVRAISON.' days');
        }
        // pas de date de livraison
        else
        {
            // par défaut J+5
            // TODO
            $deliveryDate = DateHeure::jPlusX(5);
        }

        // on calcul le prix sans TVA
        $buyingPriceWithoutTax = $buyingPriceWithTax / (1 + $provider->getVATRate() / 100);

        // on créé la commande fournisseur
        //TODO OrderSupplier in ProviderService
        $supplierOrder = $this->orderSupplier($supplierOrderId, null, $aIdOrders[0], TSupplierOrderStatus::ID_STATUS_PRODUCTION, $deliveryDate, $buyingPriceWithoutTax);

        // on supprime la 1er commande qu'on a lié à notre commande fournisseur
        unset($aIdOrders[0]);

        // pour chaque autre commande
        foreach($aIdOrders as $idOrder)
        {
            // on va lié la commande et la commande fournisseur
            $this->orderSupplierOrderService->createNew($idOrder, $supplierOrder->getIdSupplierOrder(), $deliveryDate);
        }

        // passage du mail en traité
        $achattodbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED);
            $this->entityManager->save($achattodbEmail);

        // tout est bon
        return true;
    }


    /** traite un email
     * @param AchattodbEmail $achattodbEmail objet de notre mail à traiter
     * @param TLockProcess $lockProcess
     * @throws \Exception
     */
    public function manageMail(Provider $provider,AchattodbEmail $achattodbEmail, TLockProcess $lockProcess)
    {
        $matchesSubject	 = array();
        $matchesBody	 = array();

        // mise à jour de l'étape
        $lockProcess->updateStage('Traitement de l\'email ' . $achattodbEmail->getId() . ' de  ' . $provider->getName());

        // on récupére le log du lockprocess
        //$this->setLog($lockProcess->getLog());

        // si il s'agit d'un mail de reception de fichiers
        if(preg_match($this->_pcreMailFileReceivedSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
           // $this->getLog()->addLogContent('Email de fichier reçu par ' . $this->getNomFour() . ' commande fournisseur ' . $matchesSubject[1]);

            // on traite le mail de reception de fichiers
            return $this->_manageMailFileReceived($achattodbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // si il s'agit d'un mail de commande en impression
        if(preg_match($this->_pcreOrderInProduction(), $achattodbEmail->getMessage(), $matchesBody))
        {
            //$this->getLog()->addLogContent('Email de commande en impression ' . $this->getNomFour() . ' commande fournisseur ' . $matchesBody[1]);

            // on traite le mail de commande en impression
            return $this->_manageMailOrderInProduction($achattodbEmail, $matchesBody[1], $matchesBody[2], $matchesBody[3]);
        }

        // si il s'agit d'un mail d'expédition
        if(preg_match($this->_pcreMailDispatchedSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
           // $this->getLog()->addLogContent('Email de commande en expédition ' . $this->getNomFour() . ' commande fournisseur ' . $matchesSubject[1]);

            // on traite le mail de reception de fichiers
            return $this->_manageMailDispatched($achattodbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // si il s'agit d'un mail d'expédition de réimpression
        if(preg_match($this->_pcreMailDispatchedReprintSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
           // $this->getLog()->addLogContent('Email de commande en expédition ' . $this->getNomFour() . ' commande fournisseur ' . $matchesSubject[1]);

            // on traite le mail de reception de fichiers
            return $this->_manageMailDispatched($achattodbEmail, $matchesSubject[1]);
        }

        // si il s'agit d'un mail de probléme bloquant
        if(preg_match($this->_pcreMailErrorSubject(), $achattodbEmail->getSubject(), $matchesSubject) || preg_match($this->_pcreMailErrorSubject2(), $achattodbEmail->getSubject(), $matchesSubject) || preg_match($this->_pcreMailErrorSubject3(), $achattodbEmail->getSubject(), $matchesSubject))
        {
            // $this->getLog()->addLogContent('Email de problème bloquant pour ' . $this->getNomFour() . ' commande fournisseur ' . $matchesSubject[1]);

            // on traite le mail de probléme bloquant
            return $this->_manageMailError($achattodbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // si il s'agit d'un mail de réclamation ou de suivi
        if(preg_match($this->_pcreMailReclamationSubject(), $achattodbEmail->getSubject(), $matchesSubject) || preg_match($this->_pcreMailFollowingSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
            //$this->getLog()->addLogContent('Email de réclamation/suivi ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesSubject[1] . '.');

            // on traite le mail de réclamation
            return $this->_manageMailReclamation($achattodbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // si il s'agit d'un mail de réclamation ou de suivi
        if(preg_match($this->_pcreMailReclamationInProgressBody(), $achattodbEmail->getMessage(), $matchesBody))
        {
            //$this->getLog()->addLogContent('Email de réclamation/suivi ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesBody[1] . '.');

            // on traite le mail de réclamation
            return $this->_manageMailReclamation($achattodbEmail, $matchesBody[1]);
        }

        // si il s'agit d'un mail de réimpression
        if(preg_match($this->_pcreMailReprintSubject(), $achattodbEmail->getSubject(), $matchesSubject) && preg_match($this->_pcreMailReprintBody(), $achattodbEmail->getMessage(), $matchesBody))
        {
            //$this->getLog()->addLogContent('Email de réimpression ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesSubject[1] . '.');

            // on traite le mail de réclamation
            return $this->_manageMailReprint($achattodbEmail, $matchesSubject[1], $matchesSubject[2], $matchesBody[1]);
        }

        // si il s'agit d'un mail de réimpression (autre version)
        if(preg_match($this->_pcreMailReprint2Subject(), $achattodbEmail->getSubject(), $matchesSubject) && preg_match($this->_pcreMailReprint2Body(), $achattodbEmail->getMessage(), $matchesBody))
        {
            //$this->getLog()->addLogContent('Email de réimpression ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesSubject[1] . '.');

            // on traite le mail de réclamation
            return $this->_manageMailReprint($achattodbEmail, $matchesSubject[1], $matchesSubject[2], null, $matchesBody[1]);
        }

        // si il s'agit d'un mail de réimpression en production
        if(preg_match($this->_pcreMailReprintinProductionBody(), $achattodbEmail->getMessage(), $matchesBody))
        {
            //$this->getLog()->addLogContent('Email de réimpression ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesBody[1] . '.');

            // on traite le mail de réclamation
            return $this->_manageMailReprint($achattodbEmail, $matchesBody[1], $matchesBody[2], $matchesBody[3]);
        }

        // si il s'agit d'un mail de commande non livrée suite à l'absence du destinataire
        if(preg_match($this->_pcreMailOrderNotDeliveredBody(), $achattodbEmail->getMessage(), $matchesBody))
        {
            //$this->getLog()->addLogContent('Email de commande non livrée ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesBody[1] . '.');

            // on traite le mail de commande non livrée suite à l'absence du destinataire
            return $this->_manageMailError($achattodbEmail, $matchesBody[1], $matchesBody[2]);
        }

        // si il s'agit d'un mail de commande bloquée
        if(preg_match($this->_pcreMailOrderBlockedSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
            //$this->getLog()->addLogContent('Email de commande bloquée ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesSubject[1] . '.');

            // on traite le mail de commande bloquée
            return $this->_manageMailError($achattodbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // mail inconnu
//        $lockProcess->getLog()->Erreur('mail ' . $this->getNomFour() . ' de type inconnu.');
//        $lockProcess->getLog()->Erreur('id : ' . $achattodbEmail->getId());

        return false;
    }
}