<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAVariantOptionValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAVariantOptionValue>
 *
 * @method TAVariantOptionValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAVariantOptionValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAVariantOptionValue[]    findAll()
 * @method TAVariantOptionValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAVariantOptionValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAVariantOptionValue::class);
    }
}
