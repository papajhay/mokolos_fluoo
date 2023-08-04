<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TTechnicalSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TTechnicalSheet>
 *
 * @method TTechnicalSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method TTechnicalSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method TTechnicalSheet[]    findAll()
 * @method TTechnicalSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TTechnicalSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TTechnicalSheet::class);
    }

//    /**
//     * @return TTechnicalSheet[] Returns an array of TTechnicalSheet objects
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

//    public function findOneBySomeField($value): ?TTechnicalSheet
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
