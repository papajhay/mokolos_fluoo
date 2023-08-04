<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAProductHostCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAProductHostCategory>
 *
 * @method TAProductHostCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAProductHostCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAProductHostCategory[]    findAll()
 * @method TAProductHostCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAProductHostCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAProductHostCategory::class);
    }

//    /**
//     * @return TAProductHostCategory[] Returns an array of TAProductHostCategory objects
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

//    public function findOneBySomeField($value): ?TAProductHostCategory
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
