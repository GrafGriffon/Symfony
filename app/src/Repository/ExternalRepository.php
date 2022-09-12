<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\Statement as DriverStatement;
use Doctrine\Persistence\ManagerRegistry;


class ExternalRepository extends ServiceEntityRepository
{

    protected $conn;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->conn = $managerRegistry->getConnection('customer');
    }

    public function getProducts(): array
    {
        $sql = "SELECT products.article, name, products.date_create, products.date_update, price, count FROM products
         JOIN count c on products.article = c.article
         JOIN price p on products.article = p.article";
        /** @var DriverStatement $stmt */
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute()->fetchAllAssociative();
    }


}
