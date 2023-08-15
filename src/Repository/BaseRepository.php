<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class BaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $entityManager, $entityClass)
    {
        parent::__construct($entityManager, $entityClass);
    }

    /**
     * @throws Exception
     */
    public function insert(array $data,$ignore = FALSE): void
    {
        //return $data;
        $table = $this->_class->getTableName();
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sqlBase = $ignore ? 'INSERT IGNORE INTO' : 'INSERT INTO';

        $sql = "$sqlBase $table ($columns) VALUES ($placeholders)";

        $connection = $this->_em->getConnection();
        $stmt = $connection->prepare($sql);

        $stmt->execute(array_values($data));
    }

}