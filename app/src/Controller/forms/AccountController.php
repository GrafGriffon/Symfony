<?php

namespace App\Controller\forms;

use App\Form\UserForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends AbstractController
{
    /**
     * @Route("user", methods={"GET"})
     */
    public function viewUser(Request $request): Response
    {
        $form = $this->createForm(UserForm::class, $this->getUser());
        $form->handleRequest($request);
        return $this->render('formsadding/addUser.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("user", methods={"POST"})
     */
    public function updateUser(CategoryRepository $categoryRepository, UserPasswordHasherInterface $userPasswordHasher, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserForm::class, $this->getUser());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userForm = ($form->getData());
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

}