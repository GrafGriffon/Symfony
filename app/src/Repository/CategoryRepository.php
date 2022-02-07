<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getListCategory(CategoryRepository $categoryRepository, int $page){
        $query = $categoryRepository->createQueryBuilder('u')
            ->getQuery();
        $pageSize = '5';
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $pageSize);
        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($page - 1)) // set the offset
            ->setMaxResults($pageSize); // set the limit

        $categories = array();
        return $paginator;
    }



    public function getSubcategories(CategoryRepository $categoryRepository, int $category)
    {
        $categories = [$category];
        $categories = $this->printIndex($category, $categories, $categoryRepository);
        $query = $categoryRepository->createQueryBuilder('u')
            ->where('u.id in (' . implode(", ", $categories) . ')')
            ->getQuery();
        $pageSize = '2';
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $pageSize);
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($_GET['page'] - 1)) // set the offset
            ->setMaxResults($pageSize); // set the limit
        return $paginator;
    }

    function printIndex(int $idParent, array $categories, CategoryRepository $categoryRepository): array
    {
        $category = $categoryRepository->findAll();
        foreach ($category as $item) {
            if ($item->getParent()->getId() == $idParent) {
                $categories[] = $item->getID();
                $categories = $this->printIndex($item->getId(), $categories, $categoryRepository);
            }
        }
        return $categories;
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
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
    public function findOneBySomeField($value): ?Category
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
