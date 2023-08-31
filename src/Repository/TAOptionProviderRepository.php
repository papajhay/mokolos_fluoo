<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TAOptionProvider;
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

    public function existsBy(string $idSource, int $idProvider, ?int $idProduct = 0): bool
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->where('t.provider = :idProvider')
            ->andWhere('t.optIdSource = :idSource')
            ->setParameter('idProvider', $idProvider)
            ->setParameter('idSource', $idSource);

        if ($idProduct !== null && $idProduct !== 0) {
            $queryBuilder
                ->andWhere('t.idProduct = :idProduct')
                ->setParameter('idProduct', $idProduct);
        }

        $result = $queryBuilder
            ->getQuery()
            ->getResult();

        return !empty($result);
    }

    /** Retourne un TAOptionFournisseur en fonction de l'id du fournissseur et de l'id de l'option chez le fournisseur ou null si rien n'a était trouvé. Certains paramétres supplémentaires existent pour certains fournisseurs.
     * @param string   $idOptionProviderSrc id de l'option chez le fournisseur
     * @param int|null $idProduct           [=null] id du porduit ou null si non applicable
     * @param bool     $likeSearch          [=false] mettre TRUE si on veux chercher le opt_fou_id_source avec un like
     */
    public function findByIdOptionSrc(string $idOptionProviderSrc, int $idProvider, int $idProduct = null, bool $likeSearch = false): TAOptionProvider
    {
        // paramétre de base de la requête
        $parametre = [
            'idProvider' => $idProvider,
            'idSource' => $idOptionProviderSrc
        ];

        // si on a id de produit
        if (null !== $idProduct) {
            // on ajoute les paramétre
            $parametre += [
              'idProduct' => $idProduct
            ];
        }

        // on renvoi le résultat du findBy
        return $this->findOneBy($parametre);
    }

}
