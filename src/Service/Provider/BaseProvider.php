<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\AchattodbEmail;
use App\Entity\Provider;
use App\Entity\TSupplierOrder;
use App\Entity\TSupplierOrderStatus;
use App\Repository\ProviderRepository;
use App\Service\TAProductOptionService;
use App\Service\TOptionService;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;

class BaseProvider
{
    public function __construct(
        private ProviderRepository     $providerRepository,
        protected TOptionService       $toptionService,
        protected TAProductOptionService $tAProductOptionService,
        private SupplierOrderService $supplierOrderService
    ) {
    }

    /**
     * masque pcre pour récupérer le nom du site dans une url.
     */
    private static function _pcreSiteNameFromUrl(): string
    {
        return '#https?://(?:www\.|)([-a-zA-Z]+)\.#';
    }

    /**
     * créé une nouvelle option pour ce fournisseur.
     * @param string $idOptionSource id de l'option chez le fournisseur
     * @param string $nomOption      nom de l'option
     * @param int    $idProduit      id du produi
     * @param string $idHostFusion   id du site
     */
    public function createOption(Provider $provider, string $idOptionSource, string $nomOption, int $idProduit, string $idHostFusion): TOption
    {
        // on récupére l'option ou on créé l'option si elle n'existe pas
        $option = $this->toptionService->createIfNotExist($idOptionSource, $provider->getId(), $nomOption);

        // on charge notre objet produitOption ou on le créé si il n'existe pas
        $this->tAProductOptionService->createIfNotExist($idProduit, $option, $idHostFusion, null, TAProduitOption::STATUS_ACTIF, '', '');

        // on renvoi l'option
        return $option;
    }

    /** créé une nouvelle option value pour ce fournisseur.
     */
    //    public function createOptionValue(Provider $provider, TOption $option, string $nomOptionValue, string $idOptionValueSource, int $idProduct, string $idHostFusion = 'lig'): void
    //    {
    //        // création de l'option value si elle n'existe pas
    //        $optionValue = $this->optionValueService->createIfNotExist($idOptionValueSource, $provider->getId(), $option, $nomOptionValue);
    //
    //        // on charge notre objet produitOption ou on le créé si il n'existe pas
    //        $this->productOptionService->createIfNotExist($idProduct, $option, $idHostFusion, null, TAProduitOption::STATUS_ACTIF, '', '');
    //
    //        // liaison de l'option value avec le produit
    //        $produitOptionValue = $this->productOptionValueService->createIfNotExist($idProduct, $optionValue, $idHostFusion);
    //
    //        // modification de la date de derniére vue pour ne pas la supprimer automatiquement car elle n'est pas créé automatiquement
    //        $futur = new \DateTimeImmutable('01/01/2050');
    //        $produitOptionValue->setProOptDateLastSeen($futur)
    //            ->save();
    //    }

    /**
     * Recherche un id Provider par rapport a son nom.
     * @return int|false on renvoi l'id ou false si on ne trouve pas
     */
    public function supplierIdBySupplierInformation(string $supplierInformation): ?array
    {
        $return = [];
        $matches = [];

        // si on a une url
        if (preg_match($this->_pcreSiteNameFromUrl(), $supplierInformation, $matches)) {
            // on ajoute le nom du Provider aux information pour le trouver aprés.
            $supplierInformation .= "\n".$matches[1];
        }

        // recherche de tous les fournisseurs on tri par nom inversé pour chercher "print 24 belgique" avant "print 24"
        $allSupplier = $this->providerRepository->findAllByNameDesc();

        // on sépare chaque ligne
        $allSupplierInformationLineRaw = explode("\n", $supplierInformation);

        // pour chaque ligne dans l'information
        foreach ($allSupplierInformationLineRaw as $idSupplierInformationLineRaw => $supplierInformationLineRaw) {
            // on met en minuscule
            $supplierInformationLine = trim(mb_strtolower($supplierInformationLineRaw));

            // si on n'a pas d'information
            if ('' === $supplierInformationLine) {
                // on passe au suivant
                continue;
            }

            // on recherche parmis les Provider supplémentaire
            foreach (Provider::$_ADDITIONAL_SUPPLIER as $supplierName => $supplierId) {
                // si on n'a pas trouvé
                if (mb_strtolower($supplierName) !== $supplierInformationLine) {
                    // on passe à la suivante
                    continue;
                }

                // on ajoute l'id du fournissur
                $return['idSupplier'] = $supplierId;

                // on supprime cette ligne de l'information
                unset($allSupplierInformationLineRaw[$idSupplierInformationLineRaw]);

                // on rajoute les informations
                $return['supplierInformation'] = implode("\n", $allSupplierInformationLineRaw);

                // on renvoi le résultat
                return $return;
            }

            // pour chaque Provider
            foreach ($allSupplier as $supplier) {
                // si on n'a pas trouvé
                if (mb_strtolower($supplier->getName()) !== $supplierInformationLine) {
                    // on passe à la suivante
                    continue;
                }

                // on ajoute l'id du fournissur
                $return['idSupplier'] = $supplier->getId();

                // on supprime cette ligne de l'information
                unset($allSupplierInformationLineRaw[$idSupplierInformationLineRaw]);

                // on rajoute les informations
                $return['supplierInformation'] = implode("\n", $allSupplierInformationLineRaw);

                // on renvoi le résultat
                return $return;
            }
        }

        // on recherche parmis les Provider supplémentaire
        foreach (Provider::$_ADDITIONAL_SUPPLIER as $supplierName => $supplierId) {
            // si on n'a pas trouvé
            if (!preg_match('#^'.preg_quote($supplierName).'#i', $supplierInformation)) {
                // on passe à la suivante
                continue;
            }

            // on ajoute l'id du fournissur
            $return['idSupplier'] = $supplierId;

            // on renvoi les informations en supprimant le Provider
            $return['supplierInformation'] = trim(str_replace(mb_strtolower($supplierName), '', mb_strtolower($supplierInformation)));

            // on renvoi le résultat
            return $return;
        }

        // on recherche parmis les Provider
        foreach ($allSupplier as $supplier) {
            // si on n'a pas trouvé
            if (!preg_match('#^'.preg_quote($supplier->getName()).'#i', $supplierInformation)) {
                // on passe à la suivante
                continue;
            }

            // on ajoute l'id du fournissur
            $return['idSupplier'] = $supplier->getId();

            // on renvoi les informations en supprimant le Provider
            $return['supplierInformation'] = trim(str_replace(mb_strtolower($supplier->getName()), '', mb_strtolower($supplierInformation)));

            // on renvoi le résultat
            return $return;
        }

        // on n'a rien trouvé
        return false;
    }

    /**
     * fait un findById mais renvoi l'objet spécifique du fournisseur comme fournisseurPrint24 pour p24 ou renvoi un objet fournisseur par défaut.
     * @return fournisseur|fournisseurPrint24|FournisseurLgi|FournisseurOnline et de nombreux autres
     */
    public static function findByIdWithChildObject(Provider $provider, int $idProvider): Provider
    {
        // si ce fournisseur à sa propre classe
        if (isset($provider::$_classeDeFournisseur[$idProvider])) {
            // on renverra un objet de cette classe
            $classeName = $provider::$_classeDeFournisseur[$idProvider];
        }
        // pas de classe spécifique
        else {
            // on renvoi un objet fournisseur
            $classeName = __CLASS__;
        }

        // on renvoi notre objet
        return $classeName::findById($idProvider);
    }

    /**
     * indique si le fournisseur est actif.
     * @return bool true si le fournisseur est actif et false sinon
     */
    public function isActive(Provider $provider): bool
    {
        // fournisseur inactif
        if ($provider->getActive() < Provider::SUPPLIER_ACTIVE) {
            return false;
        }

        return true;
    }


    /**
     * Récupére la commande Provider ou la créé si elle n'existe pas puis la met à jour
     * @param string $supplierOrderId id de la commande chez le Provider
     * @param int $idSupplierOrderStatus [=TSupplierOrderStatus::ID_STATUS_PRODUCTION] statut de la commande Provider
     * @param AchattodbEmail|null $achatToDbEmail [=null] si on fournit un mail il sera mis à retraité en cas de probléme
     * @param int|int[]|null $idOrder [=null] id de la commande ou des commandes si on a un array (pour une eventuelle création)
     * @param DateTime|null $deliveryDate [=null] date de livraison de la commande Provider ou null pour ne pas la mettre à jour
     * @param float|null $ordSupOrdPriceWithoutTax [=null] prix d'achat HT de la commande Provider (pour une eventuelle création)
     * @param string|null $jobId [=null] id du job ou null si non applicable
     * @param string $additionalComment [=''] commentaire additionnel à ajouter dans l'historique de la commande
     * @param int|null $idOrderStatus [=null] id du statut pour notre commande si on souhaite le changer. mettre null pour avoir un commentaire
     * @param array|null $aDeliveryInformation [=null] tableau des informations colis pour le passage de commande en livraison ou null si non applicable
     * @return TSupplierOrder|false la commande Provider ou FALSE si rien ne correspond
     * @throws NonUniqueResultException
     */
    public function updateOrderSupplier(Provider $provider, string $supplierOrderId, int $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRODUCTION, AchattodbEmail $achatToDbEmail = null, array|int $idOrder = null, \DateTime $deliveryDate = null, float $ordSupOrdPriceWithoutTax = null, string $jobId = null, string $additionalComment = '', int $idOrderStatus = null, array $aDeliveryInformation = null): TSupplierOrder|bool
    {
        // on recherche la commande Provider
        $supplierOrder = $this->supplierOrderService->orderSupplier($provider,$supplierOrderId, $achatToDbEmail, $idOrder, $idSupplierOrderStatus, $deliveryDate, $ordSupOrdPriceWithoutTax, $jobId);

        // si on n'a pas trouvé la commande Provider
        if($supplierOrder == false)
        {
            // on quitte la fonction
            return false;
        }

        // relie la commande Provider à toutes les commandes nécessaire
        $supplierOrder->linkWithAllOrder($idOrder);

        // récupération du statut de la commande
        $supplierOrderStatus = TSupplierOrderStatus::findById(array($idSupplierOrderStatus));

        // si on a mis à jour le statut
        if($supplierOrder->updateStatusIfAfterCurrent($idSupplierOrderStatus))
        {
            // on ajoutera un commentaire dans l'historique pour la commande
            $comment = 'La commande ' . $this->getNomFour() . ' "' . $supplierOrderId . '" est en "' . $supplierOrderStatus->getSupOrdStaName() . '".<br>';
        }
        // pas de maj du statut
        else
        {
            // pas de commentaire
            $comment = '';
        }

        // si on a un commentaire additionnel
        if($additionalComment != '')
        {
            // on l'ajoute
            $comment .= $additionalComment . '<br>';
        }

        // on vériei si il y a bien des commande lié à notre commande Provider
        if(!$this->checkOrderLinkedToSupplierOrderWithJob($supplierOrder, $jobId, $achatToDbEmail))
        {
            // on quitte la fonction
            return false;
        }

        // pour chaque commande correspondant à notre job
        foreach($supplierOrder->getAOrderSupplierOrder($jobId) as $orderSupplierOrder)
        {
            // si on a changé la date de livraison
            if($deliveryDate != null && $deliveryDate->format(DateHeure::DATEMYSQL) != $orderSupplierOrder->getOrdSupOrdDeliveryDate())
            {
                // on modifie la date de livraison
                $orderSupplierOrder->setOrdSupOrdDeliveryDate($deliveryDate->format(DateHeure::DATEMYSQL))
                    ->save();

                // on ajoute un commentaire
                $comment .= 'Nouvelle date de livraison : ' . $deliveryDate->format(DateHeure::DATEFR);
            }

            // si on passe la commande en expédié
            if($idOrderStatus == OrdersStatus::STATUS_EXPEDITION)
            {
                // on passe la commande en livraison
                $orderSupplierOrder->getOrder()->setAsLivraison($this->getNomFour(), $aDeliveryInformation, $comment, $this->getNomFour());
            }
            elseif($idOrderStatus == OrdersStatus::STATUS_LIVRE)
            {
                // on passe la commande en livre
                $orderSupplierOrder->getOrder()->setAsDelivered($comment, $this->getNomFour());
            }
            // si on doit changer la commande de statut
            elseif($idOrderStatus != null)
            {
                // mise à jour de la commande
                $orderSupplierOrder->getOrder()->updateStatus($idOrderStatus, $comment, OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, '', '', $this->getNomFour());
            }
            // si on a un commentaire à ajouter
            elseif(trim($comment) != '')
            {
                // on ajoute un historique à la commande
                $orderSupplierOrder->getOrder()->addHistory($comment, 0, 0, '', '', $this->getNomFour());
            }
        }

        // passage du mail en traité
        $achatToDbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED)
            ->save();

        // tout est bon
        return true;
    }
}
