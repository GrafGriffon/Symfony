<?php

namespace App\Controller\pages;

use App\Entity\Category;
use App\Entity\Products;
use App\Repository\ProductsRepository;
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
    public function show(int $category, ProductsRepository $repository): Response
    {
        $categories = [$category];
        $categories = $this->printIndex($category, $categories);
        $products = $repository->findAll();


        $query = $repository->createQueryBuilder('u')
            ->where('u.category in (' . implode(", ", $categories) . ')')
            ->getQuery();
        $pageSize = '5';
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            foreach ($query->getResult() as $element) {
                $arrayOutput[] = $element;
            }
            return $this->render('index/products.html.twig', [
                'elements' => $arrayOutput,
                'page' => 0,
                'url' => stristr($_SERVER['REQUEST_URI'], '?', true)
            ]);
        }
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $pageSize);
        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($page - 1)) // set the offset
            ->setMaxResults($pageSize); // set the limit

        $arrayOutput = array();
        foreach ($paginator as $element) {
            $arrayOutput[] = $element;
        }
        return $this->render('index/products.html.twig', [
            'elements' => $arrayOutput,
            'page' => $page,
            'url' => stristr($_SERVER['REQUEST_URI'], '?', true)
        ]);
    }

    function printIndex(int $idParent, array $categories): array
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();
        foreach ($category as $item) {
            if ($item->getParent()->getId() == $idParent) {
                $categories[] = $item->getID();
                $categories = $this->printIndex($item->getId(), $categories);
            }
        }
        return $categories;
    }
}