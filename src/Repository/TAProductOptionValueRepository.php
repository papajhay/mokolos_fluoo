<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAProductOptionValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAProductOptionValue>
 *
 * @method TAProductOptionValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAProductOptionValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAProductOptionValue[]    findAll()
 * @method TAProductOptionValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAProductOptionValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAProductOptionValue::class);
    }

//    /**
//     * @return TAProductOptionValue[] Returns an array of TAProductOptionValue objects
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

//    public function findOneBySomeField($value): ?TAProductOptionValue
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
