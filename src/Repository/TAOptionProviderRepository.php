<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAOptionProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAOptionProvider>
 *
 * @method TAOptionProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAOptionProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAOptionProvider[]    findAll()
 * @method TAOptionProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAOptionProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAOptionProvider::class);
    }

    public function save(TAOptionProvider $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TAOptionProvider $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TAOptionProvider[] Returns an array of TAOptionProvider objects
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

//    public function findOneBySomeField($value): ?TAOptionProvider
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
