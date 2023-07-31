<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TMessage>
 *
 * @method TMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TMessage[]    findAll()
 * @method TMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TMessage::class);
    }
}
