<?php declare(strict_types=1);

namespace App\Service;

use App\Repository\TAProductOptionRepository;
use Doctrine\ORM\EntityManagerInterface;

class TAProductOptionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TAProductOptionRepository $productOptionRepository
    ) {
    }

    /** créé un produitOption si il n'existe pas.
     * @param TOption     $option        option
     * @param string      $idHost        id du site
     * @param string|null $defaultValue  [=NULL] valeur par défaut utilisé dans les option de type text
     * @param int         $proOptIsActif [=TAProduitOption::STATUS_ACTIF] indique si cette option est active ou non
     * @param string      $minValue      [=''] Valeur minimum pour les options de type texte si applicable
     * @param string      $maxValue      [=''] Valeur maximum pour les options de type texte si applicable
     */
    public function createIfNotExist(int $idProduct, TOption $option, string $idHost, string $defaultValue = null, int $proOptIsActif = TAProduitOption::STATUS_ACTIF, string $minValue = '', string $maxValue = ''): void
    {
        $today = new \DateTimeImmutable();
        // on recherche notre TAProduitOption
        $produitOption = $this->productOptionRepository->findById($idProduct, $option->getIdOption(), $idHost);

        // si notre produit option n'existe pas encore
        if (null === $produitOption->getIdProduct()) {
            // on va donc créé ce produit option
            $produitOption = new TAProduitOption();
            $produitOption->setIdProduit($idProduct)
                    ->setIdOption($option->getIdOption())
                    ->setIdHost($idHost)
                    ->setLibelle($option->getOptLibelle())
                    ->setDefaultValue($defaultValue)
                    ->setOptionMinValue($minValue)
                    ->setOptionMaxValue($maxValue)
                    ->setIsActif($proOptIsActif)
                    ->setDateLastSeen($today)
                    ->reloadPrimaryValue();

            // si une ligne existe dans la table hors localisation
            // TODO existRow
            if ($produitOption->existRow()) {
                //  TODO saveJustLocalization
                // on sauvegarde uniquement la localisation
                $produitOption->saveJustLocalization();
            }
            // ce produit option value n'existe pas du tout
            else {
                // on le créé
                $this->productOptionRepository->save($produitOption);
            }
        }
        // si on a déjà ce produit option et que la derniere vu et plus ancienne que le jour meme
        elseif ($produitOption->lastSeenString() !== $today) {
            // on met à jour la date de derniére vu
            $produitOption->setDateLastSeen($today);
            $this->productOptionRepository->save($produitOption);
        }
    }
}
