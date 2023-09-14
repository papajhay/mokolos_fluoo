<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BaseRepository;

/**
 * @extends \App\Repository\BaseRepository
 *
 * @method TOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method TOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method TOption[]    findAll()
 * @method TOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TOptionRepository extends \App\Repository\BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TOption::class);
    }

    public function save(TOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findById(int $idOption): ?TOption
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.id = :id')
            ->setParameter('id', $idOption)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function prepareSelectAndExecuteAndFetchAll2(
        array $fields,
        string $orderBy
    ): array
    {
        return [];
    }

    /**
     * @throws NonUniqueResultException
     */
//    public function insertOrUpdate(TOption $option): void
//    {
//        $existingEntry = $this->createQueryBuilder('t')
//            ->where('t.id = :id')
//            ->setParameter('id', $option->getId())
//            ->getQuery()
//            ->getOneOrNullResult();
//
//        $entityManager = $this->getEntityManager();
//
//        if ($existingEntry) {
//            foreach ($data as $field => $value) {
//                $setter = 'set' . ucfirst($field);
//                if (method_exists($existingEntry, $setter)) {
//                    $existingEntry->$setter($value);
//                }
//            }
//
//            $entityManager->flush();
//        } else {
//            // Insert a new entry
//            $newEntry = new VotreEntity();
//            $newEntry->setProvider($idProvider);
//            $newEntry->setOptIdSource($idSource);
//            if ($idProduct !== null && $idProduct !== 0) {
//                $newEntry->setIdProduct($idProduct);
//            }
//            // Set other fields using the provided data array
//            foreach ($data as $field => $value) {
//                $setter = 'set' . ucfirst($field);
//                if (method_exists($newEntry, $setter)) {
//                    $newEntry->$setter($value);
//                }
//            }
//
//            $entityManager->persist($newEntry);
//            $entityManager->flush();
//        }
//    }
}
