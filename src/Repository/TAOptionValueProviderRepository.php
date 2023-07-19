<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAOptionValueProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAOptionValueProvider>
 *
 * @method TAOptionValueProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAOptionValueProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAOptionValueProvider[]    findAll()
 * @method TAOptionValueProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAOptionValueProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAOptionValueProvider::class);
    }

    public function save(TAOptionValueProvider $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TAOptionValueProvider $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return TAOptionValueProvider[] Returns an array of TAOptionValueProvider objects
     */
    public function findById($idProvider, $idOptionValue): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.idProvider= :idprovider')
            ->andWhere('t.idOptionValue= :idoptionvalue')
            ->setParameter('idprovider', $idProvider)
            ->setParameter('idoptionvalue', $idOptionValue)
            ->getQuery()
            ->getResult();
    }
}
