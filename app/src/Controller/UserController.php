<?php

namespace App\Controller;


use App\Entity\User;
use App\Handler\UserHandler;
use App\Validation\EditUserValidator;
use App\Validation\RegistrationValidator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use function Composer\Autoload\includeFile;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/api/user", name="post_api")
 */
class UserController extends ApiController
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
    public function editUser(?User $user, EntityManagerInterface $em, UserHandler $handler, Request $request): JsonResponse
    {
        if (!$user) {
            throw $this->createNotFoundException('This user does not exist');
        }
        if ($this->getUser() !== $user) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        $request = $this->transformJsonBody($request);
        $errors = (new EditUserValidator())->validate($request->request->all());
        if (!empty($errors)) {
            throw new ValidatorException('Введённые данные некорректны: ' . implode('; ', $errors));
        }
        $handler->editUser($user, $request);
        $em->flush();
        return new JsonResponse('Successfully', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/new", name="newUser", methods={"POST"})
     */
    public function createUser(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $request = $this->transformJsonBody($request);
        $errors = (new RegistrationValidator())->validate($request->request->all());
        if (!empty($errors)) {
            throw new ValidatorException('Введённые данные некорректны: ' . implode('; ', $errors));
        }

        $username = $request->get('username');
        $password = $request->get('password');
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $email = $request->get('email');
        $phone = $request->get('phone');

        if (empty($username) || empty($password) || empty($email) || empty($firstName) || empty($lastName)) {
            return $this->respondValidationError("Invalid data");
        }

        $user = new User($username, $email, $firstName, $lastName, $phone);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();
        return $this->respondWithSuccess(sprintf('User %s successfully created', $user->getUsername()));
    }
}
