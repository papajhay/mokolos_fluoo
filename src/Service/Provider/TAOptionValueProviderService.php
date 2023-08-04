<?php declare(strict_types=1);

namespace App\Service\Provider;

use App\Repository\TAOptionValueProviderRepository;

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
    public function findByIdOptionValueSrc($idOptionValueFourSrc, int $idProvider, int $idOption = null): array
    {
        // si on cherche avec une id option
        if (null !== $idOption) {
            return $this->optionValueProviderRepository->findByIdProviderAndIdOptionAndIdSource($idProvider, $idOption, $idOptionValueFourSrc);
        } else {
            return $this->optionValueProviderRepository->findByIdProviderAndIdOption($idProvider, $idOption);
        }
    }
}
