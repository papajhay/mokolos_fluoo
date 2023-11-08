<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TProductHost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TProductHost>
 *
 * @method TProductHost|null find($id, $lockMode = null, $lockVersion = null)
 * @method TProductHost|null findOneBy(array $criteria, array $orderBy = null)
 * @method TProductHost[]    findAll()
 * @method TProductHost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TProductHostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TProductHost::class);
    }

    public function save(TProductHost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TProductHost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
