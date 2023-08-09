<?php

namespace App\Service;

use App\Entity\TProduct;

class TProductService
{
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

}