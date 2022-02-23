<?php

namespace App\Repository;

use App\Entity\Products;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    public function getListProduct($user)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.supply', 'sp')
            ->innerJoin(User::class, 'u', "WITH", 'u.supply = sp.id')
            ->where("u.id=:userId")
            ->setParameter('userId', $user)
            ->getQuery();
    }

    public function getProductSupply($user, $prod)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.supply', 'sp')
            ->innerJoin(User::class, 'u', "WITH", 'u.supply = sp.id')
            ->where("u.id=:userId")
            ->andWhere("p.name=:prodName")
            ->setParameter('userId', $user)
            ->setParameter('prodName', $prod)
            ->getQuery();
    }

    public function checkProduct($user, $id)
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->innerJoin('p.supply', 'sp')
            ->innerJoin(User::class, 'u', "WITH", 'u.supply = sp.id')
            ->andWhere("p.id=:prodId")
            ->andWhere("u.id=:userId")
            ->setParameter('prodId', $id)
            ->setParameter('userId', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Products[] Returns an array of Products objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Products
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
