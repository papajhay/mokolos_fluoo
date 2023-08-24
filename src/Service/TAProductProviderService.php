<?php

namespace App\Service;

use App\Entity\Provider;
use App\Entity\TAProductProvider;
use App\Repository\ProviderRepository;
use App\Repository\TAProductProviderRepository;
use Doctrine\ORM\EntityManagerInterface;

class TAProductProviderService
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->entityManager = $entityManager;
    }
    public function save(Provider $provider, int $idSource, string $libelle):void
    {

        $productProvider = new TAProductProvider();
        $productProvider->setProvider($provider);
        $productProvider->setIdSource($idSource);
        $productProvider->setLibelleSource($libelle);

        $this->entityManager->persist($productProvider);
        $this->entityManager->flush();

    }
}