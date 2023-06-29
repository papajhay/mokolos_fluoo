<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TProduct>
 *
 * @method TProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method TProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method TProduct[]    findAll()
 * @method TProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TProduct::class);
    }

    public function save(TProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
