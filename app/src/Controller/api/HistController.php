<?php

namespace App\Controller\api;

use App\Entity\Category;
use App\Entity\Products;
use App\Repository\CategoryRepository;
use App\Repository\CountHistRepository;
use App\Repository\PriceHistRepository;
use App\Repository\UserRepository;
use App\Validation\CategoryValidator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/api", name="post_api")
 */
class HistController extends AbstractController
{
    /**
     * @return JsonResponse
     * @Route("/price-hist/{product}", methods={"GET"})
     */
    public function viewPrice(PaginatorInterface $paginator, Request $request, PriceHistRepository $repository, Products $product)
    {
        $products = $paginator->paginate(
            $repository->findBy(['product' => $product]),
            $request->query->getInt('page', 1),
            20
        );
        $data[] = ['name' => $product->getName()];
        foreach ($products as $price) {
            $data[] = [
                'price' => $price->getCurrentPrice(),
                'delta' => $price->getDelta(),
                'date' => $price->getDate()
            ];
        }
        return new JsonResponse($data);
    }

    /**
     * @return JsonResponse
     * @Route("/count-hist/{product}", methods={"GET"})
     */
    public function viewCount(PaginatorInterface $paginator, Request $request, CountHistRepository $repository, Products $product)
    {
        $products = $paginator->paginate(
            $repository->findBy(['product' => $product]),
            $request->query->getInt('page', 1),
            20
        );
        $data[] = ['name' => $product->getName()];
        foreach ($products as $price) {
            $data[] = [
                'count' => $price->getCount(),
                'delta' => $price->getDelta(),
                'date' => $price->getDate()
            ];
        }
        return new JsonResponse($data);
    }

}