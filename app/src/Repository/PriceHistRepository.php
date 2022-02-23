<?php

namespace App\Repository;

use App\Entity\PriceHist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PriceHist|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceHist|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceHist[]    findAll()
 * @method PriceHist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceHistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceHist::class);
    }

    // /**
    //  * @return PriceHist[] Returns an array of PriceHist objects
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
    public function findOneBySomeField($value): ?PriceHist
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
