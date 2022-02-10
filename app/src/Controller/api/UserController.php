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
     * @Route("/add-user", name="user_add", methods={"POST"})
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
     * @Route("/update-user/{user}", name="user_put", methods={"PUT"})
     */
    public function updateUser(Request $request, EntityManagerInterface $entityManager, User $user)
    {
        try {
            $request = $this->transformJsonBody($request);

            $user
                ->setEmail($request->get('mail'))
                ->setRoles([$request->get('role')])
                ->setFirstName($request->get('first'))
                ->setLastName($request->get('last'));
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
     * @return JsonResponse
     * @Route("/delete-user/{user}", name="user_delete", methods={"DELETE"})
     */
    public function deleteUser(EntityManagerInterface $entityManager, User $user)
    {
        $entityManager->remove($user);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "Post deleted successfully",
        ];
        return new JsonResponse($data);
    }

    /**
     * @return JsonResponse
     * @Route("/user/{user}", name="user-id-view", methods={"GET"})
     */
    public function viewUser(User $user)
    {
        $data = [
            'email' => $user->getEmail(),
            'role' => $user->getRoles(),
            'fname' => $user->getFirstName(),
            'lname' => $user->getLastName()
        ];
        return new JsonResponse($data);
    }

    /**
     * @return JsonResponse
     * @Route("/user/", name="user-view", methods={"GET"})
     */
    public function viewUsers(UserRepository $repository)
    {
        $users = $repository->findAll();
        $data = array();
        foreach ($users as $user) {
            $data[] = [
                'email' => $user->getEmail(),
                'role' => $user->getRoles(),
                'fname' => $user->getFirstName(),
                'lname' => $user->getLastName()
            ];
        }
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