<?php

namespace App\Controller;


use App\Entity\User;
use App\Handler\UserHandler;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function Composer\Autoload\includeFile;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/api/user", name="post_api")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list", name="listUsers", methods={"GET"})
     */
    public function getUsers(UserHandler $handler): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return new JsonResponse($handler->getListUsers());
    }

    /**
     * @Route("/profile", name="profileUser", methods={"GET"})
     */
    public function getProfile(UserHandler $handler): JsonResponse
    {
        return new JsonResponse($handler->getUserProfile($this->getUser()));
    }

    /**
     * @Route("/{user}", name="deleteUser", methods={"DELETE"}, requirements={"page"="\d+"})
     */
    public function deleteUser(?User $user, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$user) {
            throw $this->createNotFoundException('This user does not exist');
        }
        $em->remove($user);
        $em->flush();
        return new JsonResponse('Successfully', Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/{user}", name="editUser", methods={"PATCH"}, requirements={"page"="\d+"})
     */
    public function editUser(?User $user, EntityManagerInterface $em): JsonResponse
    {
        if (!$user) {
            throw $this->createNotFoundException('This user does not exist');
        }
        if ($this->getUser() !== $user) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $em->flush();
        return new JsonResponse('Successfully', Response::HTTP_ACCEPTED);
    }
}
