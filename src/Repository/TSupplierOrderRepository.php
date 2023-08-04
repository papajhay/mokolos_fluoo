<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TSupplierOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TSupplierOrder>
 *
 * @method TSupplierOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method TSupplierOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method TSupplierOrder[]    findAll()
 * @method TSupplierOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TSupplierOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TSupplierOrder::class);
    }

    public function save(TSupplierOrder $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TSupplierOrder $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllBySupplierOrderIdAndIdProvider($supplierOrderId, $idProvider): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.idProvider = :idprovider')
            ->andWhere('t.supplierOrderId = :supplierOrderId')
            ->setParameter('idprovider', $idProvider)
            ->setParameter('supplierOrderId', $supplierOrderId)
            ->getQuery()
            ->getResult();
    }
}
