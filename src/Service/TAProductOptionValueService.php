<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Hosts;
use App\Entity\TAProductOptionValue;
use App\Entity\TOptionValue;
use App\Entity\TProduct;
use App\Enum\StatusEnum;
use App\Repository\TAProductOptionValueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class TAProductOptionValueService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TAProductOptionValueRepository $tAProductOptionValueRepository
    ) {
    }

    /**
     * créé un productOptionValue si il n'existe pas.
     * @param TProduct $tProduct id du produit
     * @param TOptionValue $optionValue optionValue
     * @param Hosts $host id du site
     * @param int $order order (100 par défaut)
     * @return TAProductOptionValue le nouvel objet
     * @throws NonUniqueResultException
     */
     public function createIfNotExist(TProduct $tProduct, TOptionValue $optionValue, Hosts $host, int $order = 100, StatusEnum $isActive = StatusEnum::STATUS_ACTIVE): TAProductOptionValue
    {
        $today = new \DateTimeImmutable();
        // on recherche notre TAProduitOptionValue
        $tAProductOptionValue = $this->tAProductOptionValueRepository->findByProductOptionValueHost($tProduct, $optionValue, $host);

        // si notre produit option n'existe pas encore
        if (null === $tAProductOptionValue) {
            // on va donc créé ce produit optionValue
            $tAProductOptionValue = new TAProductOptionValue();
            $tAProductOptionValue->setTProduct($tProduct)
                ->setTOptionValue($optionValue)
                ->setHost($host)
                ->setLibelle($optionValue->getLibelle())
                ->setOrder($order)
                ->setDateLastSeen($today)
                ->setStatus($isActive);

            // todo set localization
              $this->entityManager->persist($tAProductOptionValue);
              $this->entityManager->flush();
        }
        // si on a déjà ce produit et que la derniere vu et plus ancienne que le jour meme
        elseif ($tAProductOptionValue->lastSeenString() !== $today) {
            // on met à jour la date de derniére vu
            $tAProductOptionValue->setDateLastSeen($today);
            $this->entityManager->flush();
        }

        return $tAProductOptionValue;
    }
}
