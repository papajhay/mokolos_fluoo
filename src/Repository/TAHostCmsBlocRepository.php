<?php

namespace App\Repository;

use App\Entity\TAHostCmsBloc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAHostCmsBloc>
 *
 * @method TAHostCmsBloc|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAHostCmsBloc|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAHostCmsBloc[]    findAll()
 * @method TAHostCmsBloc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAHostCmsBlocRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAHostCmsBloc::class);
    }

    public function findByHostAndInfoColDCms($idHost, $idOption)
    {
        return $this->createQueryBuilder('t')
            ->where('t.idHost= :idHost')
            ->andWhere('t.idOption= :idoption')
            ->setParameter('idHost', $idHost)
            ->setParameter('idoption', $idOption)
            ->getQuery()
            ->getResult();
    }
}
