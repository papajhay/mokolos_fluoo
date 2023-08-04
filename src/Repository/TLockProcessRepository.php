<?php
declare(strict_types=1);
namespace App\Repository;

use App\Entity\TLockProcess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TLockProcess>
 *
 * @method TLockProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method TLockProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method TLockProcess[]    findAll()
 * @method TLockProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TLockProcessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TLockProcess::class);
    }

    public function save(TLockProcess $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TLockProcess $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
