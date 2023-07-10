<?php
declare(strict_types=1);
namespace App\Repository;

use App\Entity\AchattodbEmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AchattodbEmail>
 *
 * @method AchattodbEmail|null find($id, $lockMode = null, $lockVersion = null)
 * @method AchattodbEmail|null findOneBy(array $criteria, array $orderBy = null)
 * @method AchattodbEmail[]    findAll()
 * @method AchattodbEmail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchattodbEmailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AchattodbEmail::class);
    }

    public function save(AchattodbEmail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AchattodbEmail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
