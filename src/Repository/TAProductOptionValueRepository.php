<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAProductOption;
use App\Entity\TAProductOptionValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAProductOptionValue>
 *
 * @method TAProductOptionValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAProductOptionValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAProductOptionValue[]    findAll()
 * @method TAProductOptionValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAProductOptionValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAProductOptionValue::class);
    }

    /**
     * @return TAProductOption[] Returns an array of TAProductOption objects
     */
    public function findById($idProduct, $idOptionValue, $idHost): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.idProduct= :idproduct')
            ->andWhere('t.idOptionValue= :idoptionvalue')
            ->andWhere('t.idHost= :idhost')
            ->setParameter('idproduct', $idProduct)
            ->setParameter('idoptionvalue', $idOptionValue)
            ->setParameter('idhost', $idHost)
            ->getQuery()
            ->getResult();
    }
}
