<?php

namespace App\Controller\forms;

use App\Entity\Products;
use App\Handler\AddCountHistoryHandler;
use App\Handler\AddPriceHistoryHandler;
use App\Form\ProductForm;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{

    /**
     * @Route("product", name="add_product_get", methods={"GET"})
     */
    public function addProductGetForm(CategoryRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $params = $request->request->all();
        $form = $this->createForm(ProductForm::class);
        $form->handleRequest($request);
        return $this->render('formsadding/addProduct.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("product", name="add_product_post", methods={"POST"})
     */
    public function addProductPostForm(CategoryRepository $repository, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);
        $productForm = $form->getData();//$request->request->all();
        $entityManager->persist($productForm);
        $entityManager->flush();
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('index/index.html.twig', [
                'categories' => $repository->findAll(),
                'idParent' => 0
            ]);
        }
        return $this->render('formsadding/addProduct.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("product/{product}", name="show_product", methods={"GET"}, requirements={"product"="\d+"})
     */
    public function updateProductGet(Products $product, ValidatorInterface $validator, ProductsRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);
        $errors = $validator->validate($product);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $this->addFlash(
                    'info',
                    $error->getMessage()
                );
            }
        }
        return $this->render('formsadding/addProduct.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("product/{product}", name="update_product", methods={"POST"})
     */
    public function updateProductPost(
        ProductsRepository     $repository,
        CategoryRepository     $categoryRepository,
        Request                $request,
        AddCountHistoryHandler $countHistoryHandler,
        AddPriceHistoryHandler $priceHistoryHandler,
        EntityManagerInterface $entityManager,
        Products               $product
    ): Response
    {
        $saveOldPrice = $product->getCurrPrice();
        $saveOldCount = $product->getStatusCount();
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);
        $productForm = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $priceHistoryHandler->addProductHist($product, $saveOldPrice, $productForm->getCurrPrice());
            $countHistoryHandler->addCountHist($product, $saveOldCount);
            $entityManager->flush();

            return $this->render('index/index.html.twig', [
                'categories' => $categoryRepository->findAll(),
                'idParent' => 0
            ]);
        }
        return $this->render('formsadding/addProduct.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("delete-product/{product}", name="delete_product_form", methods={"POST"})
     */
    public function deleteProduct(ProductsRepository $repository, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager, Products $product): Response
    {
        $entityManager->remove($product);
        $entityManager->flush();
        header('Location: http://localhost:8081/index/ ');
        return $this->render('index/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'idParent' => 0
        ]);
    }
}