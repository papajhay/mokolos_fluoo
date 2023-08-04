<?php

namespace App\Repository;

use App\Entity\TCombinaison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TCombinaison>
 *
 * @method TCombinaison|null find($id, $lockMode = null, $lockVersion = null)
 * @method TCombinaison|null findOneBy(array $criteria, array $orderBy = null)
 * @method TCombinaison[]    findAll()
 * @method TCombinaison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TCombinaisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TCombinaison::class);
    }
}
