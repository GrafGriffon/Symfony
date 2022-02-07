<?php

namespace App\Controller\api;

use App\Entity\Category;
use App\Entity\Products;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/api", name="post_api")
 */
class UserController extends AbstractController
{

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $postRepository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/posts", name="posts_add", methods={"POST"})
     */
    public function addUsr(Request $request, EntityManagerInterface $entityManager, UserRepository $postRepository)
    {

        try {
            $request = $this->transformJsonBody($request);
            $product = (new User())
                ->setRoles([1])
                ->setStatus(0)
                ->setPassword($request->get('name'))
                ->setLastName($request->get('name'))
                ->setFirstName($request->get('name'))
                ->setEmail($request->get('name'));
            $entityManager->persist($product);
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
     * @param UserRepository $repository
     * @param $id
     * @return JsonResponse
     * @Route("/posts/{id}", name="posts_put", methods={"PUT"})
     */
    public function updatePost(Request $request, EntityManagerInterface $entityManager, UserRepository $repository, $id)
    {

        try {
            $post = $repository->find($id);

            if (!$post) {
                $data = [
                    'status' => 404,
                    'errors' => "Post not found",
                ];
                return new JsonResponse($data, 404);

            }

            $request = $this->transformJsonBody($request);

            $post->setEmail($request->get('mail'));
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