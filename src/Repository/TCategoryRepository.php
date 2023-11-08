<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TCategory>
 *
 * @method TCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TCategory[]    findAll()
 * @method TCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TCategory::class);
    }
}
