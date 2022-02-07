<?php

namespace App\Controller\forms;

use App\Entity\Category;
use App\Entity\CountHist;
use App\Entity\PriceHist;
use App\Entity\Products;
use App\Handler\AddCountHistoryHandler;
use App\Handler\AddPriceHistoryHandler;
use App\Form\CategoryForm;
use App\Form\ProductForm;
use App\Form\RegistrationFormType;
use App\Repository\CategoryRepository;
use App\Repository\CountHistRepository;
use App\Repository\PriceHistRepository;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

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
    public function addProductPostForm(CategoryRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
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
     * @Route("product/{id}", name="show_product", methods={"GET"}, requirements={"product"="\d+"})
     */
    public function updateProductGet($id, ProductsRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = $repository->find($id);
        if (!$product) {
            $data = [
                'status' => 404,
                'errors' => "Post not found",
            ];
            //page error 404
        }
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);
        return $this->render('formsadding/addProduct.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("product/{id}", name="update_product", methods={"POST"})
     */
    public function updateProductPost(
        ProductsRepository     $repository,
        CategoryRepository     $categoryRepository,
        Request                $request,
        AddCountHistoryHandler $countHistoryHandler,
        AddPriceHistoryHandler $priceHistoryHandler,
        EntityManagerInterface $entityManager,
        int                    $id
    ): Response
    {
        $product = $repository->find($id);
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
     * @Route("delete-product/{id}", name="delete_product_form", methods={"POST"})
     */
    public function deleteProduct(ProductsRepository $repository, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $product = $repository->find($id);
        $entityManager->remove($product);
        $entityManager->flush();
        header('Location: http://localhost:8081/index/ ');
        return $this->render('index/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'idParent' => 0
        ]);
    }
}