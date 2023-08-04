<?php declare(strict_types=1);

namespace App\Service;

use App\Service\Provider\DateHeure;
use App\Service\Provider\System;
use App\Service\Provider\TAProduitOptionValue;
use App\Service\Provider\TOptionValue;

class TAProductOptionValueService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TAProductOptionValueRepository $productOptionValueRepository
    ) {
    }

    /**
     * créé un produitOptionValue si il n'existe pas.
     * @param  int                  $idProduct   id du produit
     * @param  TOptionValue         $optionValue optionValue
     * @param  string               $idHost      id du site
     * @param  int                  $ordre       ordre (100 par défaut)
     * @return TAProduitOptionValue le nouvel objet
     */
    public function createIfNotExist(int $idProduct, TOptionValue $optionValue, string $idHost, int $ordre = 100): TAProduitOptionValue
    {
        $today = new \DateTimeImmutable();
        // on recherche notre TAProduitOptionValue
        $produitOptionValue = $this->productOptionValueRepository->findById($idProduct, $optionValue->getIdOptionValue(), $idHost);

        // si notre produit option n'existe pas encore
        if (null === $produitOptionValue->getIdProduit()) {
            // on va donc créé ce produit optionValue
            $produitOptionValue = new TAProduitOptionValue();
            $produitOptionValue->setIdProduct($idProduct)
                ->setIdOptionValue($optionValue->getIdOptionValue())
                ->setIdHost($idHost)
                ->setLibelle($optionValue->getLibelle())
                ->setProductOptionValueOrder($ordre)
                ->setDateLastSeen($today)
                ->setIsActif($optionValue->getIsActif())
                ->reloadPrimaryValue();

            // si une ligne existe dans la table hors localisation
            if ($produitOptionValue->existRow()) {
                // on sauvegarde uniquement la localisation
                $produitOptionValue->saveJustLocalization();
            }
            // ce produit option value n'existe pas du tout
            else {
                // on le créé
                $this->productOptionValueRepository->save($produitOptionValue);
            }
        }
        // si on a déjà ce produit et que la derniere vu et plus ancienne que le jour meme
        elseif ($produitOptionValue->lastSeenString() !== $today) {
            // on met à jour la date de derniére vu
            $produitOptionValue->setProOptDateLastSeen($today);
            $this->productOptionValueRepository->save($produitOptionValue);
        }

        return $produitOptionValue;
    }
}
