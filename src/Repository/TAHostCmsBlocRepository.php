<?php

namespace App\Repository;

use App\Entity\TAHostCmsBloc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAHostCmsBloc>
 *
 * @method TAHostCmsBloc|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAHostCmsBloc|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAHostCmsBloc[]    findAll()
 * @method TAHostCmsBloc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAHostCmsBlocRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAHostCmsBloc::class);
    }

//    /**
//     * @return TAHostCmsBloc[] Returns an array of TAHostCmsBloc objects
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

//    public function findOneBySomeField($value): ?TAHostCmsBloc
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
