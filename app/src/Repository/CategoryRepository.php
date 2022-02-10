<?php

namespace App\Repository;

use App\Entity\Category;
use App\Handler\GetSubcategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

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

    public function getListCategory(CategoryRepository $categoryRepository, PaginatorInterface $paginator, Request $request)
    {
        return $paginator->paginate(
            $categoryRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
    }


    public function getSubcategories(CategoryRepository $categoryRepository, int $category, PaginatorInterface $paginator, Request $request)
    {
        return $paginator->paginate(
            $categoryRepository->createQueryBuilder('u')
                ->where('u.id in (' . implode(", ", GetSubcategories::printIndex($category, [$category], $categoryRepository)) . ')')
                ->getQuery(),
            $request->query->getInt('page', 1),
            10
        );
    }
}
