<?php

namespace App\Controller\pages;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategoryRepository;


/**
 * Class PostController
 * @package App\Controller
 * @Route("/admin", name="admin_users")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function viewPage(Request $request): Response
    {
        return $this->render('admin/main.html.twig');
    }

    /**
     * @Route("/categories/", name="index")
     */
    public function show(Request $request, CategoryRepository $repository, PaginatorInterface $paginator): Response
    {
        $products = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('admin/categories.html.twig', [
            'elements' => $products
        ]);
    }
}