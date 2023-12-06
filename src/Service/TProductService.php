<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Provider;
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
     * Retourne l'id fournisseur du produit en cour.
     */
    public function getIdProductSrc(TProduct $product): int
    {
        // on recherche le produit fournisseur source
        return $product->getTAProductProvider()->getIdSource();
    }

    public function createOrGetTProduct(TAProductProvider $tAProductProvider, int $SpecialQuantity, Provider $provider): TProduct
    {
        // Vérifier si le produit existe déjà
        $existingTProduct = $this->em->getRepository(TProduct::class)->findOneBy([
            'libelle' => $tAProductProvider->getLabelSource(),
            'specialQuantity' => $SpecialQuantity,
        ]);

        if ($existingTProduct) {
            // Le produit existe déjà, retournez-le
            return $existingTProduct;
        }

        // Le produit n'existe pas encore, créez-le
        $tProduct = new TProduct();
        $tProduct->setLibelle($tAProductProvider->getLabelSource())
                 ->setSpecialQuantity($SpecialQuantity)
                 ->setProvider($provider);

        $this->em->persist($tProduct);
        $this->em->flush();

        return $tProduct;
    }

    /**
     *  renvoi un objet TAProduitFournisseur correspondant à un fournisseur de ce produit
     * @return TAProduitFournisseur
     */
    public function getProduitFournisseur()
    {
        // si on a pas encore récupéré le produitFournisseur
        if(!isset($this->_produitFournisseur))
        {
            // on recherche le produit fournisseur source
            $this->_produitFournisseur = TAProduitFournisseur::findByIdProduit($this->getIdProduit());
        }

        return $this->_produitFournisseur;
    }
}
