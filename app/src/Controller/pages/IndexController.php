<?php

namespace App\Controller\pages;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Lcobucci\JWT\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class IndexController extends AbstractController
{
    /**
     * @Route("/index/", name="index")
     */
    public function list(CategoryRepository $repository): Response
    {
        try {
            return $this->render('index/index.html.twig', [
                'categories' => $repository->findAll(),
                'idParent' => 0
            ]);
        }
        catch (Throwable $e){
            return $this->render('error.html.twig');
        }
    }
}