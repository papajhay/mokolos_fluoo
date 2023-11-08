<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAOptionProvider;
use App\Entity\TAOrderSupplierOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAOrderSupplierOrder>
 *
 * @method TAOrderSupplierOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAOrderSupplierOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAOrderSupplierOrder[]    findAll()
 * @method TAOrderSupplierOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAOrderSupplierOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAOrderSupplierOrder::class);
    }

    public function save(TAOrderSupplierOrder $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TAOptionProvider $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByIdorderAndIdSupplierOrder($idOrder, $idSupplierOrder): ?TAOrderSupplierOrder
    {
        return $this->createQueryBuilder('o')
            ->where('o.idOrder = :idorder')
            ->andWhere('o.idSupplierOrder = :idsupplierorder')
            ->setParameter('idorder', $idOrder)
            ->setParameter('idsupplierorder', $idSupplierOrder)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
