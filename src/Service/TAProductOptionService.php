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
        private TAProductOptionRepository $productOptionRepository,
        private TOptionService $tOptionService
    ) {
    }

    /** créé un produitOption si il n'existe pas.
     * @param TOption     $option        option
     * @param string|Hosts      $host        id du site
     * @param string|null $defaultValue  [=NULL] valeur par défaut utilisé dans les option de type text
     * @param int         $proOptIsActif [=TAProduitOption::STATUS_ACTIF] indique si cette option est active ou non
     * @param string      $minValue      [=''] Valeur minimum pour les options de type texte si applicable
     * @param string      $maxValue      [=''] Valeur maximum pour les options de type texte si applicable
     */
    public function createIfNotExist(TProduct $product, TOption $option, string | Hosts $host, string $defaultValue = null, int $proOptIsActif = TAProductOption::STATUS_ACTIF, string $minValue = '', string $maxValue = ''): void
    {
        $today = new \DateTimeImmutable();
        // on recherche notre TAProduitOption;
        $taProductOption = $this->productOptionRepository->findOneBy(['product'=>$product, 'tOption' => $option,'host' => $host]);
//        $option = $this->tOptionService->createTOption();

        // si notre produit option n'existe pas encore
        if (null === $taProductOption) {
//            $host = $this->entityManager->getRepository(Hosts::class)->find($host);
            $fullHost = $this->entityManager->getRepository(Hosts::class)->findOneBy(['name'=>$host]);
//            $product = $this->entityManager->getRepository(TProduct::class)->find($product);

            // on va donc créé ce produit option
            $taProductOption = new TAProductOption();
            $taProductOption ->setProduct($product)
                             ->setTOption($option)
                             ->setHost($fullHost)
                             ->setLabel($option->getLabel())
                             ->setDefaultValue($defaultValue)
                             ->setOptionMinValue($minValue)
                             ->setOptionMaxValue($maxValue)
                             ->setIsActif($proOptIsActif)
                             ->setDateHourLastSeen($today);

            dd($taProductOption);
                // on le créé
                $this->entityManager->persist($taProductOption);
                $this->entityManager->flush();

        }
        // si on a déjà ce produit option et que la derniere vu et plus ancienne que le jour meme
        elseif($taProductOption->getDateHourLastSeen() != $today)
        {
            // on met à jour la date de derniére vu
            $taProductOption->setDateHourLastSeen($today);
            $this->entityManager->flush();

        }
    }
}
