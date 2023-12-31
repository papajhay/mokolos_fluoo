<?php declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Provider;
use App\Entity\TAOptionValueProvider;
use App\Entity\TOption;
use App\Entity\TOptionValue;
use App\Repository\TAOptionValueProviderRepository;
use Doctrine\ORM\EntityManagerInterface;

class TAOptionValueProviderService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TAOptionValueProviderRepository $optionValueProviderRepository
    ) {
    }

    /**
     * renvoi un optionValueProvider[] à partir d'un idOption du fournisseur et un id fournisseur.
     */
    public function findByIdOptionValueSrc($idOptionValueFourSrc, int $idProvider, int $idOption = null): array|TAOptionValueProvider
    {
        // si on cherche avec une id option
        if (!isset($idOption)) {
            return $this->optionValueProviderRepository->findByIdProviderAndIdOptionAndIdSource($idProvider, $idOption, $idOptionValueFourSrc);
        } else {
            return $this->optionValueProviderRepository->findByIdProviderAndIdOption($idProvider, $idOption);
        }
    }

    /**
     * créé un nouvel objet et le renvoi.
     * @param  TOptionValue          $optionValue         id de l'option value chez nous
     * @param  Provider              $provider            id du fournisseur
     * @param  string                $idOptionValueSource id de l'optionValue chez le fournisseur
     * @param  string                $nameOptionValue     nom de l'optionValue chez le fournisseur
     * @param  string                $productAlias        product alias de l'optionvalue pour le fournisseur si applicable
     * @param  int                   $elementId           elementId de l'optionvalue pour le fournisseur si applicable
     * @return TAOptionValueProvider nouvel objet
     */
    public function createNewTAOptionValueProvider(TOptionValue $optionValue, Provider $provider, string $idOptionValueSource, string $nameOptionValue, TOption $option, string $productAlias, int $elementId): TAOptionValueProvider
    {
        $TAOptionValueProvider = new TAOptionValueProvider();
        $TAOptionValueProvider->setTOptionValue($optionValue)
                              ->setProvider($provider)
                              ->setSourceKey($idOptionValueSource)
                              ->setDescription($nameOptionValue)
                              ->setTOption($option)
                              ->setProductAlias($productAlias)
                              ->setElementId($elementId);

        $this->entityManager->persist($TAOptionValueProvider);
        $this->entityManager->flush();

        return $TAOptionValueProvider;
    }
}
