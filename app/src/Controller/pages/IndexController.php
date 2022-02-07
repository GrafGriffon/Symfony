<?php

namespace App\Controller\pages;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    /**
     * @Route("/index/", name="index")
     */
    public function list(CategoryRepository $repository): Response
    {
        return $this->render('index/index.html.twig', [
            'categories' => $repository->findAll(),
            'idParent' => 0
        ]);
    }
}