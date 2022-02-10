<?php

namespace App\Controller\forms;

use App\Entity\User;
use App\Form\UserForm;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class PostController
 * @package App\Controller
 * @Route("/admin", name="admin_users")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/user", name="adduserformGET", methods={"GET"})
     */
    public function addUserGET(Request $request): Response
    {
        $form = $this->createForm(UserForm::class);
        $form->handleRequest($request);
        return $this->render('formsadding/addUser.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/user", name="adduserformPOST", methods={"POST"})
     */
    public function addUserPOST(CategoryRepository $repository, UserPasswordHasherInterface $userPasswordHasher, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);
        $userForm = $form->getData();//$request->request->all();
        $userForm->setStatus(1);
        if ($form->isSubmitted() && $form->isValid()) {
            $userForm->setPassword(
                $userPasswordHasher->hashPassword(
                    $userForm,
                    $userForm->getPassword()
                )
            );
            $entityManager->persist($userForm);
            $entityManager->flush();
            return $this->render('index/index.html.twig', [
                'categories' => $repository->findAll(),
                'idParent' => 0
            ]);
        }
        return $this->render('formsadding/addUser.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/user/{user}", name="show_user", methods={"GET"})
     */
    public function viewUser(User $user, Request $request): Response
    {
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);
        return $this->render('formsadding/addUser.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/user/{user}", name="update_user", methods={"POST"})
     */
    public function updateUser(User $user, CategoryRepository $categoryRepository, UserPasswordHasherInterface $userPasswordHasher, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userForm = $form->getData();
            $userForm->setPassword(
                $userPasswordHasher->hashPassword(
                    $userForm,
                    $userForm->getPassword()
                )
            );
            $entityManager->flush();
            return $this->render('index/index.html.twig', [
                'categories' => $categoryRepository->findAll(),
                'idParent' => 0
            ]);
        }
        return $this->render('formsadding/addUser.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("delete-user/{user}", name="delete_user_form", methods={"POST"})
     */
    public function deleteUser(UserRepository $repository, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();
        header('Location: http://localhost:8081/index/ ');
        return $this->render('index/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'idParent' => 0
        ]);
    }
}