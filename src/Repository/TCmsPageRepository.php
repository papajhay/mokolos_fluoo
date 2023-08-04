<?php

namespace App\Repository;

use App\Entity\TCmsPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TCmsPage>
 *
 * @method TCmsPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TCmsPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TCmsPage[]    findAll()
 * @method TCmsPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TCmsPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TCmsPage::class);
    }

}
