<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Provider;
use App\Entity\TAOptionProvider;
use App\Entity\TProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TAOptionProvider>
 *
 * @method TAOptionProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method TAOptionProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method TAOptionProvider[]    findAll()
 * @method TAOptionProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TAOptionProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TAOptionProvider::class);
    }

    public function save(TAOptionProvider $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TAOptionProvider $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return TAOptionProvider[] Returns an array of TAOptionProvider objects
     */
    public function findByIdOptionProvider($idProvider, $idOption): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.idProvider= :idprovider')
            ->andWhere('t.idOption= :idoption')
            ->setParameter('idprovider', $idProvider)
            ->setParameter('idoption', $idOption)
            ->getQuery()
            ->getResult();
    }

    public function existsBy(string $sourceKey, Provider $provider, TProduct $tProduct = null): bool
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->where('t.provider = :provider')
            ->andWhere('t.sourceKey= :sourceKey')
            ->setParameter('provider', $provider)
            ->setParameter('sourceKey', $sourceKey);

        if (null !== $tProduct) {
            $queryBuilder
                ->andWhere('t.tProduct = :tProduct')
                ->setParameter('tProduct', $tProduct);
        }

        $result = $queryBuilder
            ->getQuery()
            ->getResult();

        return !empty($result);
    }

    /** Retourne un TAOptionFournisseur en fonction de l'id du fournissseur et de l'id de l'option chez le fournisseur ou null si rien n'a était trouvé. Certains paramétres supplémentaires existent pour certains fournisseurs.
     * @param bool $likeSearch [=false] mettre TRUE si on veux chercher le opt_fou_id_source avec un like
     */
    public function findByIdOptionSrc(string $sourceKey, Provider $provider, TProduct $tProduct = null, bool $likeSearch = false): ?TAOptionProvider
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->where('t.provider = :provider')
            ->andWhere('t.sourceKey= :sourceKey')
            ->setParameter('provider', $provider)
            ->setParameter('sourceKey', $sourceKey)
            ->setMaxResults(1)
        ;

        if (null !== $tProduct) {
            $queryBuilder
                ->andWhere('t.tProduct = :tProduct')
                ->setParameter('tProduct', $tProduct);
        }

        return $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();
    }
}
