<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TProductHostAcl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TProductHostAcl>
 *
 * @method TProductHostAcl|null find($id, $lockMode = null, $lockVersion = null)
 * @method TProductHostAcl|null findOneBy(array $criteria, array $orderBy = null)
 * @method TProductHostAcl[]    findAll()
 * @method TProductHostAcl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TProductHostAclRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TProductHostAcl::class);
    }

    /**
     * @return TProductHostAcl[] Returns an array of TProductHostAcl objects
     */
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

//    public function findOneBySomeField($value): ?TProductHostAcl
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
