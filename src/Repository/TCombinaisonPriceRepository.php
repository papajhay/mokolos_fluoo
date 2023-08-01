<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TCombinaisonPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TCombinaisonPrice>
 *
 * @method TCombinaisonPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method TCombinaisonPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method TCombinaisonPrice[]    findAll()
 * @method TCombinaisonPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TCombinaisonPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TCombinaisonPrice::class);
    }

//    /**
//     * @return TCombinaisonPrice[] Returns an array of TCombinaisonPrice objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TCombinaisonPrice
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
