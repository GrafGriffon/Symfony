<?php

namespace App\Controller\api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Validation\CategoryValidator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
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
class CategoryController extends AbstractController
{
    /**
     * @param CategoryRepository $productRepository
     * @return JsonResponse
     * @Route("/categories", name="categories", methods={"GET"})
     */
    public function getCategories(PaginatorInterface $paginator, Request $request, CategoryRepository $categoryRepository, LoggerInterface $logger)
    {
//        $logger->
        $paginator = $categoryRepository->getListCategory($categoryRepository, $paginator, $request);
        $categories = array();
        foreach ($paginator as $category) {
            $categories[] = [
                'title' => $category->getTitle(),
                'level' => $category->getLevel()
            ];
        }
        return new JsonResponse($categories);
    }

    /**
     * @param CategoryRepository $productRepository
     * @return JsonResponse
     * @Route("/subcategories/{category}", name="subcategories", methods={"GET"})
     */
    public function getSubcategories(CategoryRepository $categoryRepository, int $category, PaginatorInterface $paginator, Request $request)
    {
        $paginator = $categoryRepository->getSubcategories($categoryRepository, $category, $paginator, $request);

        $categories = array();
        foreach ($paginator as $category) {
            $categories[] = [
                'title' => $category->getTitle(),
                'level' => $category->getLevel()
            ];
        }
        return new JsonResponse($categories);
    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param CategoryRepository $repository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/newcategory", name="newcategory", methods={"POST"})
     */
    public function addNewCategory(Request $request, EntityManagerInterface $entityManager, CategoryRepository $repository)
    {

        try {
            $request = $this->transformJsonBody($request);
            $errors = (new CategoryValidator())->validate($request->request->all());

            if (!empty($errors)) {
                throw new ValidatorException('Введённые данные некорректны: ' . implode('; ', $errors));
            }

            $category = (new Category())
            ->setTitle($request->get('title'))
            ->setParent($repository->find($request->get('parent')))
            ->setLevel($repository->find($request->get('parent'))->getLevel() + 1);
            $entityManager->persist($category);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => "Post added successfully",
            ];
            return new JsonResponse($data);

        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return new JsonResponse($data, 422);

        }
    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param CategoryRepository $repository
     * @param $id
     * @return JsonResponse
     * @Route("/udpate-category/{id}", name="update_category", methods={"PUT"})
     */
    public function updateCategory(
        Request                $request,
        EntityManagerInterface $entityManager,
        CategoryRepository     $repository,
        int                    $id)
    {

        try {
            $category = $repository->find($id);

            if (!$category) {
                $data = [
                    'status' => 404,
                    'errors' => "Post not found",
                ];
                return new JsonResponse($data, 404);

            }

            $request = $this->transformJsonBody($request);
//            $errors = (new CategoryValidator())->validate($request->request->all());
//
//            if (!empty($errors)) {
//                throw new ValidatorException('Введённые данные некорректны: ' . implode('; ', $errors));
//            }
            $category
                ->setTitle($request->get('title'))
                ->setParent($repository->find($request->get('parent')))
                ->setLevel($repository->find($request->get('parent'))->getLevel() + 1);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'errors' => "Post updated successfully",
            ];
            return new JsonResponse($data);


        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return new JsonResponse($data, 422);
        }

    }


    /**
     * @param UserRepository $repository
     * @param $id
     * @return JsonResponse
     * @Route("/delete-category/{id}", name="category_delete", methods={"DELETE"})
     */
    public function deleteCategory(EntityManagerInterface $entityManager, CategoryRepository $repository, $id)
    {
        $category = $repository->find($id);

        if (!$category) {
            $data = [
                'status' => 404,
                'errors' => "Post not found",
            ];
            return new JsonResponse($data, 404);

        }

        $entityManager->remove($category);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "Post deleted successfully",
        ];
        return new JsonResponse($data);
    }


    protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

}