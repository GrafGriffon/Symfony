<?php

namespace App\Controller\forms;

use App\Entity\Category;
use App\Form\CategoryForm;
use App\Form\ProductForm;
use App\Form\RegistrationFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
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
        $entityManager->persist($category);
        $entityManager->flush();
        $form = $this->createForm(CategoryForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("category/{id}", name="show_category", methods={"GET"})
     */
    public function updateProductGet($id, CategoryRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = $repository->find($id);
        if (!$category) {
            $data = [
                'status' => 404,
                'errors' => "Post not found",
            ];
            //page error 404
        }
        $form = $this->createForm(CategoryForm::class, $category);
        $form->handleRequest($request);
        return $this->render('formsadding/addCategory.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("category/{id}", name="update_category", methods={"POST"})
     */
    public function updateProductPost(CategoryRepository $repository, Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $product = $repository->find($id);
        $form = $this->createForm(CategoryForm::class, $product);
        $form->handleRequest($request);
        $categoryForm = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {

            //$entityManager->persist($productForm);
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
}