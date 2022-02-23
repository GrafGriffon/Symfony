<?php

namespace App\Controller\pages;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Throwable;


/**
 * Class PostController
 * @package App\Controller
 * @Route("/admin", name="admin_users")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/users", name="show_products", methods={"GET"})
     */
    public function show(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {
        try {
            $products = $paginator->paginate(
                $repository->findAll(),
                $request->query->getInt('page', 1),
                20
            );
            return $this->render('admin/index.html.twig', [
                'elements' => $products
            ]);
        }
        catch (Throwable $e){
            return $this->render('error.html.twig');
        }
    }
}