<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Hosts;
use App\Entity\TAProductOption;
use App\Entity\TOption;
use App\Entity\TProduct;
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
    public function createIfNotExist(int $idProduct, TOption $option, string $idHost, string $defaultValue = null, int $proOptIsActif = TAProductOption::STATUS_ACTIF, string $minValue = '', string $maxValue = ''): void
    {
        $today = new \DateTimeImmutable();

        // on recherche notre TAProduitOption
        $productOption = $this->productOptionRepository->findOneBy(['product_id'=>$idProduct, 'option_id'=>$option->getId(),'host_id'=>$idHost]);

        // si notre produit option n'existe pas encore
        if (null === $productOption) {

            $host = $this->entityManager->getRepository(Hosts::class)->find($idHost);
            $product = $this->entityManager->getRepository(TProduct::class)->find($idProduct);

            // on va donc créé ce produit option
            $productOption = new TAProductOption();
            $productOption->setProduct($product)
                    ->setTOption($option)
                    ->setHost($host)
                    ->setLabel($option->getLibelle())
                    ->setDefaultValue($defaultValue)
                    ->setOptionMinValue($minValue)
                    ->setOptionMaxValue($maxValue)
                    ->setIsActif($proOptIsActif)
                    ->setDateHourLastSeen($today);

                // on le créé
                $this->entityManager->persist($productOption);
                $this->entityManager->flush();

        }
        // si on a déjà ce produit option et que la derniere vu et plus ancienne que le jour meme
        elseif($productOption->getDateHourLastSeen() != $today)
        {
            // on met à jour la date de derniére vu
            $productOption->setDateHourLastSeen($today);
            $this->entityManager->flush();

        }
    }
}
