<?php

namespace App\Controller\forms;

use App\Entity\Category;
use App\Entity\User;
use App\Form\CategoryForm;
use App\Form\ProductForm;
use App\Form\RegistrationFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    /**
     * @Route("category", name="addcategoryformGET", methods={"GET"})
     */
    public function addCategoryGet(CategoryRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryForm::class);
        $form->handleRequest($request);
        return $this->render('formsadding/addCategory.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("category", name="addcategoryformPOST", methods={"POST"})
     */
    public function addCategoryPost(CategoryRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
    {

        $params = $request->request->all();
        $category = (new Category())
            ->setTitle($params['category_form']['title'])
            ->setParent($repository->find($params['category_form']['parent']))
            ->setLevel($repository->find($params['category_form']['parent'])->getLevel() + 1);
        $form = $this->createForm(CategoryForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->render('index/index.html.twig', [
                'categories' => $repository->findAll(),
                'idParent' => 0
            ]);
        }
        return $this->render('formsadding/addCategory.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("category/{category}", name="show_category", methods={"GET"})
     */
    public function viewCategory(Category $category, CategoryRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryForm::class, $category);
        $form->handleRequest($request);
        return $this->render('formsadding/addCategory.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("category/{category}", name="update_category", methods={"POST"})
     */
    public function updateCategory(Category $category, CategoryRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryForm::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryForm = $form->getData();
            $entityManager->flush();
            return $this->render('index/index.html.twig', [
                'categories' => $repository->findAll(),
                'idParent' => 0
            ]);
        }
        return $this->render('formsadding/addCategory.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("delete-category/{category}", methods={"POST"})
     */
    public function deleteCategory(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, Category $category): Response
    {
        $entityManager->remove($category);
        $entityManager->flush();
        header('Location: http://localhost:8081/index/ ');
        return $this->render('index/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'idParent' => 0
        ]);
    }
}