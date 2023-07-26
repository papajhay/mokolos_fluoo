<?php

namespace App\Repository;

use App\Entity\TAProductMeta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAProductMeta>
 *
 * @method TAProductMeta|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAProductMeta|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAProductMeta[]    findAll()
 * @method TAProductMeta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAProductMetaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAProductMeta::class);
    }

//    /**
//     * @return TAProductMeta[] Returns an array of TAProductMeta objects
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

//    public function findOneBySomeField($value): ?TAProductMeta
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
