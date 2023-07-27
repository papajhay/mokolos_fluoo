<?php

namespace App\Repository;

use App\Entity\TCmsPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TCmsPage>
 *
 * @method TCmsPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TCmsPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TCmsPage[]    findAll()
 * @method TCmsPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TCmsPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TCmsPage::class);
    }

//    /**
//     * @return TCmsPage[] Returns an array of TCmsPage objects
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

//    public function findOneBySomeField($value): ?TCmsPage
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
