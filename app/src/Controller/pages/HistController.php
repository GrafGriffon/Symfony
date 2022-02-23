<?php

namespace App\Controller\pages;

use App\Entity\Category;
use App\Entity\Products;
use App\Form\UploadForm;
use App\Handler\GetSubcategories;
use App\Repository\CategoryRepository;
use App\Repository\CountHistRepository;
use App\Repository\PriceHistRepository;
use App\Repository\ProductsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class HistController extends AbstractController
{
    /**
     * @Route("price-hist/{product}", methods={"GET"})
     */
    public function showPrice(Products $product, Request $request, PriceHistRepository $repository, PaginatorInterface $paginator): Response
    {
        try {
            $products = $paginator->paginate(
                $repository->findBy(['product' => $product]),
                $request->query->getInt('page', 1),
                20
            );
            return $this->render('hist/price.html.twig', [
                'elements' => $products
            ]);
        }
        catch (Throwable $e){
            return $this->render('error.html.twig');
        }
    }

    /**
     * @Route("count-hist/{product}", methods={"GET"})
     */
    public function showCount(Products $product, Request $request, CountHistRepository $repository, PaginatorInterface $paginator): Response
    {
        try {
            $products = $paginator->paginate(
                $repository->findBy(['product' => $product]),
                $request->query->getInt('page', 1),
                20
            );
            return $this->render('hist/count.html.twig', [
                'elements' => $products
            ]);
        }
        catch (Throwable $e){
            return $this->render('error.html.twig');
        }
    }

}