<?php declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Provider;
use App\Entity\TAOptionProvider;
use App\Entity\TOption;
use App\Entity\TProduct;
use App\Repository\TAOptionProviderRepository;

class TAOptionProviderService
{
    public function __construct(
        private TAOptionProviderRepository $optionProviderRepository
    ) {
    }

    /** Retourne TRUE si ce TAOptionFournisseur existe. Certains paramétres supplémentaires existent pour certains fournisseurs.
     * @param int $idOptionFourSrc id de l'option chez le fournisseur
     * @param int $idProvider id du fournisseur
     * @param int|null $idProduct [=null] id du porduit
     * @return bool
     */
    public function existByIdOptionSrc(string $idOptionFourSrc, int $idProvider, ?int $idProduct = 0): bool
    {
        return $this->optionProviderRepository->existsBy($idOptionFourSrc,$idProvider, $idProduct);
    }


    /** Cré un nouvel objet "TAOptionFournisseur" et le retourne.
     * @param Provider $provider le fournisseur
     * @param TOption $option unsigned $idOption id de l'option
     * @param string $idSource
     * @param string $descriptionSource
     * @param TProduct|null $product
     * @return TAOptionProvider Nouvel Objet inseré en base
     */
    public function createNew(Provider $provider, TOption $option, string $idSource, string $descriptionSource, TProduct $product = null): TAOptionProvider
    {
        $optionProvider = new TAOptionProvider();
        $optionProvider->setProvider($provider)
                        ->setTOption($option)
                        ->setOptIdSource($idSource)
                        ->setDescriptionSource($descriptionSource)
                        ->setTProduct($product);

        $this->optionProviderRepository->save($optionProvider);

        return $optionProvider;
    }
}
