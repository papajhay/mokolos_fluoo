<?php
declare(strict_types=1);
namespace App\Repository;

use App\Entity\Ttransporter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ttransporter>
 *
 * @method Ttransporter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ttransporter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ttransporter[]    findAll()
 * @method Ttransporter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TtransporterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ttransporter::class);
    }

    public function save(Ttransporter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ttransporter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


}
