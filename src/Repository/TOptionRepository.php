<?php
declare(strict_types=1);
namespace App\Repository;

use App\Entity\TOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TOption>
 *
 * @method TOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method TOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method TOption[]    findAll()
 * @method TOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TOption::class);
    }

    public function save(TOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
}
