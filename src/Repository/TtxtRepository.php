<?php

namespace App\Repository;

use App\Entity\Ttxt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ttxt>
 *
 * @method Ttxt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ttxt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ttxt[]    findAll()
 * @method Ttxt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TtxtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ttxt::class);
    }

//    /**
//     * @return Ttxt[] Returns an array of Ttxt objects
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

//    public function findOneBySomeField($value): ?Ttxt
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
