<?php

namespace App\Controller\pages;

use App\Entity\Category;
use App\Handler\GetSubcategories;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("catalog")
 */
class CatalogController extends AbstractController
{
    /**
     * @Route("/{category}", name="show_products", methods={"GET"})
     */
    public function show(int $category, Request $request, ProductsRepository $repository, CategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        try {
            $categories = GetSubcategories::printIndex($category, [$category], $categoryRepository);
            $products = $paginator->paginate(
                $repository->createQueryBuilder('u')
                    ->where('u.category in (' . implode(", ", $categories) . ')')
                    ->getQuery(),
                $request->query->getInt('page', 1),
                20
            );
//        }
            return $this->render('index/products.html.twig', [
                'elements' => $products
            ]);
        }
        catch (Throwable $e){
            return $this->render('error.html.twig');
        }
    }
}