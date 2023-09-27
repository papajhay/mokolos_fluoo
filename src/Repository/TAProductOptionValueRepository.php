<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAProductOption;
use App\Entity\TAProductOptionValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * @return TAProductOption Returns an array of TAProductOption objects
     * @throws NonUniqueResultException
     */
    public function findByProductOptionValueHost($tProduct, $tOptionValue, $host): ?TAProductOption
    {
        return $this->createQueryBuilder('t')
            ->where('t.TProduct= :tProduct')
            ->andWhere('t.TOptionValue= :tOptionValue')
            ->andWhere('t.Host= :host')
            ->setParameter('tProduct', $tProduct)
            ->setParameter('tOptionValue', $tOptionValue)
            ->setParameter('host', $host)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
