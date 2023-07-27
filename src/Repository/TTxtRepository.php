<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TTxt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TTxt>
 *
 * @method TTxt|null find($id, $lockMode = null, $lockVersion = null)
 * @method TTxt|null findOneBy(array $criteria, array $orderBy = null)
 * @method TTxt[]    findAll()
 * @method TTxt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TTxtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TTxt::class);
    }

//    /**
//     * @return TTxt[] Returns an array of TTxt objects
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

//    public function findOneBySomeField($value): ?TTxt
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
