<?php declare(strict_types=1);

namespace App\Service;

use App\Repository\TAProductOptionValueProvider;

class TAProductOptionValueProviderService
{
    public function __construct(
        private TAProductOptionValueProviderRepository $productOptionValueProviderRepository
    ) {
    }

    /**
     * Cré un nouvel objet "TAProductOptionValueFournisseur" et le retourne
     * @param int $idProvider id du fournisseur
     * @param int $idOptionValue id de l'option value
     * @param int $idProduct id du produit
     * @param string $idSource id de l'option value chez le fournisseur
     * @return TAProductOptionValueProvider Nouvel Objet inseré en base
     */
    public function createNew(int $idProvider, int $idOptionValue, int $idProduct, string $idSource): TAProductOptionValueProvider
    {
        $productOptionValueProvider = new TAProductOptionValueProvider();

        $productOptionValueProvider->setIdProvider($idProvider)
                                    ->setIdOptionValue($idOptionValue)
                                    ->setIdProduct($idProduct)
                                    ->setIdSource($idSource);
        $this->productOptionValueProviderRepository->save($productOptionValueProvider);

        return $productOptionValueProvider;
    }
}