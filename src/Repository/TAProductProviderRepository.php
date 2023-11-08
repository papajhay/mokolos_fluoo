<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAProductProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAProductProvider>
 *
 * @method TAProductProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAProductProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAProductProvider[]    findAll()
 * @method TAProductProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAProductProviderRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAProductProvider::class);
    }

    public function save(TAProductProvider $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
