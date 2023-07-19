<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Provider;
use App\Repository\OrderRepository;
use App\Repository\ProviderRepository;
use App\Repository\TSupplierOrderRepository;
use App\Repository\TSupplierOrderStatusRepository;
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
        private OrderSupplierOrderService $orderSupplierOrderService
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
}
