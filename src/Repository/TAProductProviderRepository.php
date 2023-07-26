<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAProductProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAProductProvider>
 *
 * @method TAProductProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAProductProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAProductProvider[]    findAll()
 * @method TAProductProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAProductProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAProductProvider::class);
    }

//    /**
//     * @return TAProductProvider[] Returns an array of TAProductProvider objects
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

//    public function findOneBySomeField($value): ?TAProductProvider
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
