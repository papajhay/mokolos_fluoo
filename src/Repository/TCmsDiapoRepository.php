<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TCmsDiapo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TCmsDiapo>
 *
 * @method TCmsDiapo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TCmsDiapo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TCmsDiapo[]    findAll()
 * @method TCmsDiapo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TCmsDiapoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TCmsDiapo::class);
    }

//    /**
//     * @return TCmsDiapo[] Returns an array of TCmsDiapo objects
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

//    public function findOneBySomeField($value): ?TCmsDiapo
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
