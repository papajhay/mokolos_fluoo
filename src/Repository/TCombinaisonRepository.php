<?php

namespace App\Repository;

use App\Entity\TCombinaison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TCombinaison>
 *
 * @method TCombinaison|null find($id, $lockMode = null, $lockVersion = null)
 * @method TCombinaison|null findOneBy(array $criteria, array $orderBy = null)
 * @method TCombinaison[]    findAll()
 * @method TCombinaison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TCombinaisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TCombinaison::class);
    }

//    /**
//     * @return TCombinaison[] Returns an array of TCombinaison objects
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

//    public function findOneBySomeField($value): ?TCombinaison
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
