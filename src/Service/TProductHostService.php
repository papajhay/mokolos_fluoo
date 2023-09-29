<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TProductHost;

class TProductHostService
{
    public function __construct(
    ) {
    }

    /**
     * getteur qui indique si le produit autorise les quantité personnalisé. (utilisé par l'API smartlabel).
     */
    public function getProductSpecialQuantity(TProductHost $productHost): int
    {
        return $productHost->getTProduct()->getSpecialQuantity();
    }

    /**
     * indique si le produit est un variant ou un produit original.
     * @return bool true pour un variant et false pour un original
     */
    public function isVariant(TProductHost $productHost): bool
    {
        // si il s'agit d'un produit variant
        if (TProduitHost::ID_VARIANT === $productHost->getVariant()) {
            return true;
        }

        return false;
    }
}
