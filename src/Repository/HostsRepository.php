<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Hosts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hosts>
 *
 * @method Hosts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hosts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hosts[]    findAll()
 * @method Hosts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hosts::class);
    }

    //    /**
    //     * @return Hosts[] Returns an array of Hosts objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Hosts
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
