<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Validation\CategoryValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/api", name="post_api")
 */
class CategoryController extends AbstractController
{
    /**
     * @return JsonResponse
     * @Route("/subcategories", name="subcategories", methods={"GET"})
     */
    public function getSubcategories(Request $request)
    {
        return new JsonResponse([]);
    }
}