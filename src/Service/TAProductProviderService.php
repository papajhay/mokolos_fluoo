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
    private $productProviderRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TAProductProviderRepository $productProviderRepository
    ){
        $this->entityManager = $entityManager;
        $this->productProviderRepository = $productProviderRepository;
    }
    public function save(Provider $provider, int $idSource, int $idGroup, string $label): void
    {

        $result = $this->productProviderRepository->findOneBy(['idSource'=>$idSource,'idGroup'=>$idGroup,'labelSource'=>$label]);

        if(!isset($result)) {
            $productProvider = new TAProductProvider();
            $productProvider->setProvider($provider);
            $productProvider->setIdSource($idSource);
            $productProvider->setIdGroup($idGroup);
            $productProvider->setLabelSource($label);

            $this->entityManager->persist($productProvider);
            $this->entityManager->flush();

        }
    }
}