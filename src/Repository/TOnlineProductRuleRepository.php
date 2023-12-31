<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TOnlineProductRule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TOnlineProductRule>
 *
 * @method TOnlineProductRule|null find($id, $lockMode = null, $lockVersion = null)
 * @method TOnlineProductRule|null findOneBy(array $criteria, array $orderBy = null)
 * @method TOnlineProductRule[]    findAll()
 * @method TOnlineProductRule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TOnlineProductRuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TOnlineProductRule::class);
    }

    //    /**
    //     * @return TOnlineProductRule[] Returns an array of TOnlineProductRule objects
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

    //    public function findOneBySomeField($value): ?TOnlineProductRule
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
