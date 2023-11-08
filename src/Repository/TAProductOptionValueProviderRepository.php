<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAProductOptionValueProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAProductOptionValueProvider>
 *
 * @method TAProductOptionValueProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAProductOptionValueProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAProductOptionValueProvider[]    findAll()
 * @method TAProductOptionValueProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAProductOptionValueProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAProductOptionValueProvider::class);
    }

    //    /**
    //     * @return TAProductOptionValueProvider[] Returns an array of TAProductOptionValueProvider objects
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

    //    public function findOneBySomeField($value): ?TAProductOptionValueProvider
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
