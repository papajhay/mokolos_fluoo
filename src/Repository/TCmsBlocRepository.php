<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TCmsBloc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TCmsBloc>
 *
 * @method TCmsBloc|null find($id, $lockMode = null, $lockVersion = null)
 * @method TCmsBloc|null findOneBy(array $criteria, array $orderBy = null)
 * @method TCmsBloc[]    findAll()
 * @method TCmsBloc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TCmsBlocRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TCmsBloc::class);
    }

}
