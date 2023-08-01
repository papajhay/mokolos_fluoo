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
}
