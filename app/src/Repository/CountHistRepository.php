<?php

namespace App\Repository;

use App\Entity\CountHist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CountHist|null find($id, $lockMode = null, $lockVersion = null)
 * @method CountHist|null findOneBy(array $criteria, array $orderBy = null)
 * @method CountHist[]    findAll()
 * @method CountHist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountHistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CountHist::class);
    }

    // /**
    //  * @return CountHist[] Returns an array of CountHist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CountHist
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
