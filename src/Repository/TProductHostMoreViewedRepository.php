<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TProductHostMoreViewed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TProductHostMoreViewed>
 *
 * @method TProductHostMoreViewed|null find($id, $lockMode = null, $lockVersion = null)
 * @method TProductHostMoreViewed|null findOneBy(array $criteria, array $orderBy = null)
 * @method TProductHostMoreViewed[]    findAll()
 * @method TProductHostMoreViewed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TProductHostMoreViewedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TProductHostMoreViewed::class);
    }
}
