<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAProductOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAProductOption>
 *
 * @method TAProductOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAProductOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAProductOption[]    findAll()
 * @method TAProductOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAProductOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAProductOption::class);
    }

    public function save(TAProductOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TAProductOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return TAProductOption[] Returns an array of TAProductOption objects
     */
    public function findById($idProduct, $idOption, $idHost): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.idProduct= :idproduct')
            ->andWhere('t.idOption= :idoption')
            ->andWhere('t.idHost= :idhost')
            ->setParameter('idproduct', $idProduct)
            ->setParameter('idoption', $idOption)
            ->setParameter('idhost', $idHost)
            ->getQuery()
            ->getResult();
    }
}
