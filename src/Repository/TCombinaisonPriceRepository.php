<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TCombinaisonPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TCombinaisonPrice>
 *
 * @method TCombinaisonPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method TCombinaisonPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method TCombinaisonPrice[]    findAll()
 * @method TCombinaisonPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TCombinaisonPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TCombinaisonPrice::class);
    }
}
