<?php
declare(strict_types=1);

namespace App\Service\Provider\Print24;


use App\Entity\AchattodbEmail;
use App\Entity\Provider;
use App\Entity\TLockProcess;
use App\Entity\TSupplierOrderStatus;
use App\Entity\Ttransporter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class ManageMail extends BasePrint24
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     *  cette fonction traite les mails qui arrive sur la boite achat
     * @param AchattodbEmail $achatToDbEmail objet de notre mail à traiter
     * @param TLockProcess $lockProcess
     * @param Provider $provider
     * @return bool
     */
    public function manageMail(AchattodbEmail $achatToDbEmail, TLockProcess $lockProcess,  Provider $provider): bool
    {
        $matchesSubject	 = [];
        $matchesBody	 = [];

        $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de ' . $provider->getName());

        // on récupére le log du lockprocess
//        $this->setLog($lockProcess->getLog());

        // traitement email de confirmation de commande
        if(preg_match($this->_pcreMailOrderConfirmationSubject(), $achatToDbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de confirmation de commande ' . $provider->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            return $this->_manageMailOrderConfirmation($achatToDbEmail, $matchesSubject[1]);
        }

        // si il s'agit d'un mail de fichier impossible à télécharger
        if(preg_match($this->_pcreMailFileErrorDownloading(), $achatToDbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de commande en erreur à cause de fichier impossible à téécharger ' . $provider->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // on traite comme un mail d'erreur
            return $this->_manageMailFileError($achatToDbEmail, $matchesSubject[1], null);
        }

        // si il s'agit d'un mail de fichier manquant
        if(preg_match($this->_pcreMailWorkingStateSubject(), $achatToDbEmail->getSubject(), $matchesSubject) && preg_match($this->_pcreMailMissingFileBody(), $achatToDbEmail->getMessage()))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de commande en erreur en attente de nouveau fichier ' . $provider->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // on traite comme un mail d'erreur
            return $this->_manageMailFileError($achatToDbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // traitement email de fichier reçu
        if(preg_match($this->_pcreMailFileReceived(), $achatToDbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de fichier reçu ' . $provider->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // traitement du mail
            return $this->_manageMailFileReceived($achatToDbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // traitement email de Confirmation de contrôle
        if(preg_match($this->_pcreMailFileValidate(), $achatToDbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de fichier validé ' . $provider->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            return $this->_manageMailFileValidate($achatToDbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // traitement email passage en fabrication
        if(preg_match($this->_pcreMailOrderStateSubject(), $achatToDbEmail->getSubject(), $matchesSubject) && preg_match($this->_pcreMailProduction(), $achatToDbEmail->getMessage()))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de passage en fabrication ' . $provider->getName() . ' pour la commande ' . $matchesSubject[1]);

            // traitement du mail
            return $this->_manageMailProduction($achatToDbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // on va chercher s'il s'agit d'un mail d'expedition
        if(preg_match($this->_pcreMailDispatchedSubject(), $achatToDbEmail->getSubject()))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' d\'expedition ' . $provider->getName());

            // on traite l'email
            return $this->_manageMailDispatched($achatToDbEmail, $provider);
        }

        // traitement email facture
        if(preg_match($this->_pcreMailInvoice(), $achatToDbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de facture ' . $provider->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // gestion du mail
            return $this->_manageMailInvoice($achatToDbEmail, $matchesSubject[1], $provider);
        }

        // traitement email d'évaluation
        if(preg_match($this->_pcreMailEvaluationSubject(), $achatToDbEmail->getSubject()))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' d\'évaluation ' . $provider->getName());

            // passage du mail en traité
            $achatToDbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED);
                          $this->entityManager->persist($achatToDbEmail);
                          $this->entityManager->flush();

            // on quitte la fonction
            return true;
        }

        // si il s'agit d'un mail d'une commande en erreur
        if(preg_match($this->_pcreMailErrorFileSubject(), $achatToDbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de commande en erreur ' . $provider->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // on traite le mail d'erreur
            return $this->_manageMailFileError($achatToDbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // si il s'agit d'un mail d'une commande en erreur nécessitant de nouveau fichier
        if(preg_match($this->_pcreMailWorkingStateSubject(), $achatToDbEmail->getSubject(), $matchesSubject) && preg_match($this->_pcreMailErrorNeedNewFileBody(), $achatToDbEmail->getMessage()))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de commande en erreur en attente de nouveau fichier ' . $provider->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // on traite le mail d'erreur
            return $this->_manageMailFileError($achatToDbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // traitement email d'annulation
        if(preg_match($this->_pcreMailOrderStateSubject(), $achatToDbEmail->getSubject(), $matchesSubject) && preg_match($this->_pcreMailOrderCanceledBody(), $achatToDbEmail->getMessage()))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' d\'annulation ' . $provider->getName());

            // on traite le mail d'annulation
            return $this->_manageMailOrderCanceled($achatToDbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // traitement email de facture d'annulation
        if(preg_match($this->_pcreMailCancelInvoice(), $achatToDbEmail->getSubject()))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de facture d\'annulation ' . $provider->getName());

            // on traite le mail de facture d'annulation
            return $this->_manageMailOrderCancelInvoice($achatToDbEmail);
        }

        // traitement email de crédit d'impression
        if(preg_match($this->_pcreMailPrintCredit(), $achatToDbEmail->getSubject()))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de crédit d\impression ' . $provider->getName());

            // passage du mail en traité
            $achatToDbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED);
            $this->entityManager->persist($achatToDbEmail);
            $this->entityManager->flush();

            // tout est bon
            return true;
        }

        // traitement email de délai
        if(preg_match($this->_pcreMailDelaySubject(), $achatToDbEmail->getSubject(), $matchesSubject) && preg_match($this->_pcreMailDelayBody(), $achatToDbEmail->getMessage(), $matchesBody))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' de délai ' . $provider->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // gestion du mail
            return $this->_manageMailDelay($achatToDbEmail, $matchesSubject[1], $matchesSubject[2], $matchesBody[1], $matchesBody[2], $provider);
        }

        // traitement email d'offre de sauvegarde de donnée
        if(preg_match($this->_pcreMailDataSaveSubject(), $achatToDbEmail->getSubject()))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' d\'offre de sauvegarde de donnée ' . $provider->getName());

            // passage du mail en traité
            $achatToDbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED);
            $this->entityManager->persist($achatToDbEmail);
            $this->entityManager->flush();

            // tout est bon
            return true;
        }

        // traitement email d'avoir
        if(preg_match($this->_pcreMailCreditNoteSubject(), $achatToDbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achatToDbEmail->getId() . ' d\'avoir ' . $provider->getName() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // gestion du mail
            return $this->_manageMailOrderCancelInvoice($achatToDbEmail);
        }

        // autre mail
//        $lockProcess->getLog()->Error('mail ' . $provider->getName() . ' de type inconnu.');
//        $lockProcess->getLog()->Error('id : ' . $achatToDbEmail->getId());

        return false;
    }

    /**
     * Traitement du mail de confirmation de commande
     * @param AchattodbEmail $achatToDbEmail Objet AchattodbEmail
     * @param string $supplierOrderId Identifiant de la commande fournisseur
     * @return bool true en cas de succés et false en cas de probléme
     * @throws NonUniqueResultException
     */
    private function _manageMailOrderConfirmation(AchattodbEmail $achatToDbEmail, string $supplierOrderId): bool
    {
        // on met à jour la commande fournisseur
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_FILE_WAITING, $achatToDbEmail);
    }

    /**
     * Traitement du mail de fichier recu
     * @param AchattodbEmail $achatToDbEmail Objet AchattodbEmail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @param string $jobId numéro du job
     * @return bool true si tout se passe bien et false en cas de probléme
     * @throws NonUniqueResultException
     */
    private function _manageMailFileReceived(AchattodbEmail $achatToDbEmail, string $supplierOrderId, string $jobId): bool
    {
        // on recherche la commande fournisseur ou la créé au besoin
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_FILE_RECEIVED, $achatToDbEmail, null, null, null, $jobId);
    }

    /**
     * Traitement du mail de fichier validé
     * @param AchattodbEmail $achatToDbEmail Objet AchattodbEmail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @param string $jobId numéro du job
     * @return bool true si tout se passe bien et false en cas de probléme
     * @throws NonUniqueResultException
     */
    private function _manageMailFileValidate(AchattodbEmail $achatToDbEmail, string $supplierOrderId, string $jobId): bool
    {
        // on recherche la commande fournisseur ou la créé au besoin
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_FILE_VALID, $achatToDbEmail, null, null, null, $jobId);
    }

    /**
     * Traitement du mail de passage en fabrication
     * @param AchattodbEmail $achatToDbEmail Objet AchattodbEmail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @param string $jobId numéro du job
     * @return bool true si tout se passe bien et false en cas de probléme
     * @throws NonUniqueResultException
     */
    private function _manageMailProduction(AchattodbEmail $achatToDbEmail, $supplierOrderId, $jobId): bool
    {
        // on met à jour la commande fournisseur
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_PRODUCTION, $achatToDbEmail, null, null, null, $jobId);
    }

    /**
     * Traitement du mail de délai
     * @param AchattodbEmail $achatToDbEmail Objet AchattodbEmail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @param string $jobId numéro du job
     * @param string $supplierDate date au format du fournisseur
     * @param string $supplierDateMax date maximum de livraison au format du fournisseur
     * @param bool $idFromFour =FALSE mettre TRUE si $orderId est un numéro fournisseur et FALSE si c'est notre numéro de commande
     * @throws NonUniqueResultException
     */
    private function _manageMailDelay(AchattodbEmail $achatToDbEmail, string $supplierOrderId, string $jobId, string $supplierDate, string $supplierDateMax, Provider $provider): \App\Entity\TSupplierOrder|bool
    {
        // on transforme en objet date
        $deliveryDate = new DateHeure($supplierDate);

        // commentaire commande
        $info = $provider->getName() . ' est en retard. Nouvelle livraison prévue le ' . $deliveryDate->format(DateHeure::DATEFR) . '.';

        // si on a une date de livraison max
        if($supplierDateMax != NULL)
        {
            // on transforme en objet date
            $dateMax = new DateHeure($supplierDateMax);

            // on la rajoute dans le commentaire
            $info .= ' Date de livraison maximum le ' . $dateMax->format(DateHeure::DATEFR) . '.';
        }

        // on met à jour la commande fournisseur
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_PRODUCTION, $achatToDbEmail, null, $deliveryDate, null, $jobId, $info, OrdersStatus::STATUS_FABRICATION_RETARD);
    }

    /**
     * Traitement du mail de facture
     * @param AchattodbEmail $achatToDbEmail Objet AchattodbEmail
     * @param int $supplierOrderId numéro de la commande chez le fournisseur
     * @return bool true en cas de succés et false en cas de probléme
     * @throws NonUniqueResultException
     */
    private function _manageMailInvoice(AchattodbEmail $achatToDbEmail, int $supplierOrderId, Provider $provider): bool
    {
        // en cas de probléme lors de la copie de la facture
        if(!$this->_copyInvoice($achatToDbEmail))
        {
            // on quitte la fonction
            return false;
        }

        // on recherche la commande fournisseur ou la créé au besoin
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_DISPATCHED, $achatToDbEmail, null, null, null, null, $provider->getName() . ' a généré une facture.');
    }

    /**
     * Traitement du mail de facture d'annulation ou d'avoir
     * @param AchattodbEmail $achatToDbEmail	Objet AchattodbEmail
     * @return bool true en cas de succés et false en cas de probléme
     */
    private function _manageMailOrderCancelInvoice(AchattodbEmail $achatToDbEmail): bool
    {
        // en cas de probléme lors de la copie de la facture
        if(!$this->_copyInvoice($achatToDbEmail))
        {
            // on quitte la fonction
            return false;
        }

        // passage du mail en traité
        $achatToDbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED);
           $this->entityManager->persist($achatToDbEmail);
           $this->entityManager->flush();

        return true;
    }

    /**
     * traite un email d'expédition
     * @param AchattodbEmail $achatToDbEmail le mail
     * @return boolean TRUE si on a réussi à récupérer les infos et FALSE si on a un probléme
     * @throws NonUniqueResultException
     */
    private function _manageMailDispatched(AchattodbEmail $achatToDbEmail, Provider $provider): bool
    {
        //Notre tableau qui contient type du colis, les numero command et l'url
        $aDeliveryInformation = [];

        // on initialise les tableau qui vont contenir les numéros de colis et les url de tracking
        $listeColis		 = [];
        $listeUrlColis	 = [];

        $resultatsLienExpeditionP24	 = [];
        $resultatNumeroColis		 = [];
        $matchesSupplierOrderId		 = [];

        // si on n'arrive pas à récupéré le numéro de commande fournisseur
        if(!preg_match($this->_pcreMailDispatchedBody(), $achatToDbEmail->getMessageHtml(), $matchesSupplierOrderId))
        {
            // on ajoute un message d'erreur
            $this->getLog()->Error('Impossible de trouver le numéro de commande fournisseur.');
            return false;
        }

        $this->getLog()->addLogContent('Email de commande en expédition pour la commande fournisseur ' . $matchesSupplierOrderId[1]);

        // on va récupéré tous les liens et leur contenu dans le mail
        if(!preg_match_all('#<a href=["\']([^"\']*)["\']>([^<]*)</a>#', $achatToDbEmail->getMessageHtml(), $resultatsLienExpeditionP24))
        {
            // on renvoi une erreur
            $this->getLog()->Error('Aucun lien trouvé dans un email d\'expédition de ' . $provider->getName() . '.');
            return false;
        }

        // on analyse le premier numéro de colis pour savoir si il s'agit de dhl
        if(preg_match('#dhl\.com#', $resultatsLienExpeditionP24[1][0]))
        {
            $this->getLog()->addLogContent('Livré par DHL');

            // pour chaque url présent dans le mail
            foreach($resultatsLienExpeditionP24[1] AS $urlColis)
            {
                $originalUrlColis = Tools::getOriginalUrlFromVadeUrl($urlColis);

                // si c'est bien un lien dhl (c'est pas un lien pour adobe reader) et qu'on n'a pas encore ce numéro de colis
                if(preg_match($this->_pcreUrlDHL(), $originalUrlColis, $resultatNumeroColis) && !in_array($resultatNumeroColis[1], $listeColis))
                {
                    // on met à jour nos tabeau de colis et le nombre de colis
                    $listeColis[]	 = $resultatNumeroColis[1];
                    $listeUrlColis[] = $originalUrlColis;
                }
            }

            // si on n'a pas trouvé de colis
            if(count($listeColis) < 1)
            {
                // on renvoi une erreur
                $this->getLog()->Error('Aucun numéro de colis dans le email d\'expédition de ' . $provider->getName() . '.');
                $this->getLog()->Error(var_export($resultatsLienExpeditionP24, true));
            }

            // création du texte du colis :
            $aDeliveryInformation['idTransporteur']	 = TTransporter::ID_TRANSPORTEUR_DHL;
            $aDeliveryInformation['transporteur']	 = 'DHL';
            $aDeliveryInformation['numColis']		 = $listeColis;
            $aDeliveryInformation['urlColis']		 = $listeUrlColis;
            $aDeliveryInformation['shippingDate']	 = $achatToDbEmail->getDateHeureSend();
        }
        // commande envoyé par DPD
        elseif(isset($resultatsLienExpeditionP24[1][1]) && preg_match($this->_pcreUrlDPD(), $resultatsLienExpeditionP24[1][1]) && preg_match_all($this->_pcreParcelDPD(), $achatToDbEmail->getMessageHtml(), $resultatNumeroColis))
        {
            // récupération du transporteur
            $transporteur = TTransporter::findByIdWithChildObject(TTransporter::ID_CARRIER_DPD_EUROPE);

            // ajout d'info au log
            $this->getLog()->addLogContent('Livré par ' . $transporteur->getTraNomComplet());

            // création du texte du colis :
            $aDeliveryInformation['idTransporteur']	 = $transporteur->getIdTransporteur();
            $aDeliveryInformation['transporteur']	 = $transporteur->getTraNomComplet();
            $aDeliveryInformation['numColis']		 = array();
            $aDeliveryInformation['urlColis']		 = array();
            $aDeliveryInformation['shippingDate']	 = $achatToDbEmail->getDateHeureSend();

            // pour chaque numéro de colis
            foreach($resultatNumeroColis[1] AS $numeroColis)
            {
                // ajout du numéro de colis
                $aDeliveryInformation['numColis'][] = $numeroColis;

                // ajout de l'url du colis
                $aDeliveryInformation['urlColis'][] = 'https://tracking.dpd.de/parcelstatus?query=' . $numeroColis . '&locale=fr_FR';
            }
        }
        // pas de numéro de colis
        elseif(preg_match('#http://www.adobe.[a-z]{2,3}/products/acrobat/readstep2.html#', $resultatsLienExpeditionP24[2][0]))
        {
            //Schenker sans numéro de colis
            if(preg_match('#Schenker#', $achatToDbEmail->getMessageHtml()))
            {
                $this->getLog()->addLogContent('Livré par Schenker sans numéro de colis');

                // création du texte du colis :
                $aDeliveryInformation['idTransporteur']	 = TTransporter::ID_TRANSPORTEUR_SHENKER;
                $aDeliveryInformation['transporteur']	 = 'SHENKER';
                $aDeliveryInformation['shippingDate']	 = $achatToDbEmail->getDateHeureSend();
            }
            // inconnu sans numéro de colis
            else
            {
                $this->getLog()->Error('Transporteur inconnu sans lien de suivi');
            }
        }
        // on n'a pas trouvé le transporteur
        else
        {
            $this->getLog()->Error('Transporteur inconnu');
            $this->getLog()->Error(var_export($resultatsLienExpeditionP24, true));
        }

        // on recherche la commande fournisseur ou la créé et la met à jour au besoin
        return $this->updateOrderSupplier($matchesSupplierOrderId[1], TSupplierOrderStatus::ID_STATUS_DISPATCHED, $achatToDbEmail, null, null, null, $matchesSupplierOrderId[2], null, OrdersStatus::STATUS_EXPEDITION, $aDeliveryInformation);
    }

    /**
     * traite un email d'erreur de fichier
     * @param AchattodbEmail $achatToDbEmail le mail
     * @param int $supplierOrderId l'id de la commande chez le fournisseur
     * @param string $jobId numéro du job
     * @return boolean TRUE si on a réussi à récupérer les infos et FALSE si on a un probléme
     * @throws NonUniqueResultException
     */
    private function _manageMailFileError(AchattodbEmail $achatToDbEmail, int $supplierOrderId, string $jobId): bool
    {
        // on met à jour la commande fournisseur
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_ERROR, $achatToDbEmail, null, null, null, $jobId, $achatToDbEmail->getMessage(), OrdersStatus::STATUS_DEPART_FAB_RETOUR);
    }

    /**
     * traite un email d'annulation de commande
     * @param AchattodbEmail $achatToDbEmail le mail
     * @param int $supplierOrderId l'id de la commande chez le fournisseur
     * @param string $jobId numéro du job
     * @return boolean TRUE si on a réussi à récupérer les infos et FALSE si on a un probléme
     */
    private function _manageMailOrderCanceled(AchattodbEmail $achatToDbEmail, int $supplierOrderId, string $jobId): bool
    {
        // on met à jour la commande fournisseur
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_CANCELED, $achatToDbEmail, null, null, null, $jobId);
    }
}

