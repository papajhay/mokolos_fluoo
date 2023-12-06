<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Provider;
use App\Entity\TAProductProvider;
use App\Repository\TAProductProviderRepository;
use Doctrine\ORM\EntityManagerInterface;

class TAProductProviderService
{
    private EntityManagerInterface $entityManager;
    private $productProviderRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TAProductProviderRepository $productProviderRepository
    ) {
        $this->entityManager = $entityManager;
        $this->productProviderRepository = $productProviderRepository;
    }

    public function save(Provider $provider, int $idSource, int $idGroup, string $label): void
    {
        $result = $this->productProviderRepository->findOneBy(['idSource' => $idSource, 'idGroup' => $idGroup, 'labelSource' => $label]);

        if (!isset($result)) {
            $productProvider = new TAProductProvider();
            $productProvider->setProvider($provider);
            $productProvider->setIdSource($idSource);
            $productProvider->setIdGroup($idGroup);
            $productProvider->setLabelSource($label);

            $this->entityManager->persist($productProvider);
            $this->entityManager->flush();
        }
    }

    /**
     *  renvoi un objet TAProduitFournisseur correspondant à un fournisseur de ce produit
     * @return TAProductProvider
     */
    public function getProductProvider(): TAProductProvider
    {
        // si on a pas encore récupéré le produitFournisseur
        if(!isset($this->_productProvider))
        {
            // on recherche le produit fournisseur source
            $this->_productProvider = TAProductProvider::findByIdProduit($this->getIdProduit());
        }

        return $this->_productProvider;
    }
}
