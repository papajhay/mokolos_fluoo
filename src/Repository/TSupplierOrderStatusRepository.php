<?php
declare(strict_types=1);
namespace App\Repository;

use App\Entity\TSupplierOrderStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TSupplierOrderStatus>
 *
 * @method TSupplierOrderStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method TSupplierOrderStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method TSupplierOrderStatus[]    findAll()
 * @method TSupplierOrderStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TSupplierOrderStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TSupplierOrderStatus::class);
    }

    public function save(TSupplierOrderStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TSupplierOrderStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return TSupplierOrderStatus Returns an array of TAOptionProvider objects
     */
    public function findById($idSupplierOrderStatus): ?TSupplierOrderStatus
    {
        return $this->createQueryBuilder('t')
            ->where('t.id= :idsupplierStatus')
            ->setParameter('idsupplierStatus', $idSupplierOrderStatus)
            ->getQuery()
            ->getOneOrNullResult();
    }


}
