<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TOptionValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TOptionValue>
 *
 * @method TOptionValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TOptionValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TOptionValue[]    findAll()
 * @method TOptionValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TOptionValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TOptionValue::class);
    }

//    /**
//     * @return TOptionValue[] Returns an array of TOptionValue objects
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

//    public function findOneBySomeField($value): ?TOptionValue
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
