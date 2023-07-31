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
     * @param smallint(6) $idFournisseur id du fournisseur
     * @param int(11) $idOptionValue id de l'option value
     * @param mediumint(9) $idProduct id du produit
     * @param varchar(250) $proOptValFouIdSource id de l'option value chez le fournisseur
     * @return TAProductOptionValueFournisseur Nouvel Objet inseré en base
     */
    public function createNew($idProvider, $idOptionValue, $idProduct, $id)
    {
        $productOptionValueProvider = new TAProductOptionValueProvider();

        $productOptionValueProvider->setIdProvider($idProvider)
                                    ->setIdOptionValue($idOptionValue)
                                    ->setIdProduct($idProduct)
                                    ->setId($id);
        $this->productOptionValueProviderRepository->save($productOptionValueProvider);

        return $productOptionValueProvider;
    }
}