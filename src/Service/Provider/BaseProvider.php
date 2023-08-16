<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Provider;
use App\Repository\OrderRepository;
use App\Repository\ProviderRepository;
use App\Repository\TSupplierOrderRepository;
use App\Repository\TSupplierOrderStatusRepository;
use App\Service\Provider\TAProduitOption;
use App\Service\Provider\TOption;
use App\Service\Provider\TOptionValueService;
use App\Service\TAProductOptionService;
use App\Service\TAProductOptionValueService;
use App\Service\TOptionService;
use Doctrine\ORM\EntityManagerInterface;

class BaseProvider
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private OrderRepository $orderRepository,
        private TSupplierOrderRepository $TSupplierOrderRepository,
        private ProviderRepository $providerRepository,
        private TSupplierOrderStatusRepository $supplierOrderStatusRepository,
        private AchattodbEmailService $achattodbEmailService,
        private OrderSupplierOrderService $orderSupplierOrderService,
        private TOptionService $toptionService,
        //private TOptionValueService $optionValueService,
        private TAProductOptionService $productOptionService,
        private TAProductOptionValueService $productOptionValueService
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
        $this->productOptionService->createIfNotExist($idProduit, $option, $idHostFusion, null, TAProduitOption::STATUS_ACTIF, '', '');

        // on renvoi l'option
        return $option;
    }

    /** créé une nouvelle option value pour ce fournisseur.
     * @param TOption $option              l'option lié à notre option value
     * @param string  $nomOptionValue      le nom de l'option value
     * @param string  $idOptionValueSource l'id de l'option value chez le fournisseur
     * @param string  $idHostFusion        id du site
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
     * fait un findById mais renvoi l'objet spécifique du fournisseur comme fournisseurPrint24 pour p24 ou renvoi un objet fournisseur par défaut
     * @param int $idFour type de fournisseur
     * @return fournisseur|fournisseurPrint24|FournisseurLgi|FournisseurOnline et de nombreux autres
     */
    public static function findByIdWithChildObject(Provider $provider,int $idProvider):Provider
    {
        // si ce fournisseur à sa propre classe
        if(isset($provider::$_classeDeFournisseur[$idProvider]))
        {
            // on renverra un objet de cette classe
            $classeName = $provider::$_classeDeFournisseur[$idProvider];
        }
        // pas de classe spécifique
        else
        {
            // on renvoi un objet fournisseur
            $classeName = __CLASS__;
        }

        // on renvoi notre objet
        return $classeName::findById($idProvider);
    }
    /**
     * indique si le fournisseur est actif
     * @return boolean true si le fournisseur est actif et false sinon
     */
    public function isActive(Provider $provider): bool
    {
        // fournisseur inactif
        if($provider->getActive() < Provider::SUPPLIER_ACTIVE)
        {
            return false;
        }

        return true;
    }
}
