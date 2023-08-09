<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TOptionValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TOptionValue>
 *
 * @method TOptionValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TOptionValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TOptionValue[]    findAll()
 * @method TOptionValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TOptionValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TOptionValue::class);
    }
    public function save(TOptionValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
