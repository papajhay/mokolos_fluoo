<?php

namespace App\Service;

use App\Entity\TAProductProvider;
use App\Entity\TProduct;
use Doctrine\ORM\EntityManagerInterface;

class TProductService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * Retourne l'id fournisseur du produit en cour
     *
     * @return int
     */
    public function getIdProductSrc(TProduct $product): int
    {
        // on recherche le produit fournisseur source
        return $product->getTAProductProvider()->getIdSource();
    }

    public function createProduct( TAProductProvider $TAProductProvider, int $SpecialQuantity): TProduct
    {
        $product = new TProduct();
        $product->setLibelle($TAProductProvider->getLabelSource())
                ->setSpecialQuantity($SpecialQuantity);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

}