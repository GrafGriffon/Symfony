<?php

namespace App\Controller\forms;

use App\Entity\CountHist;
use App\Form\UploadForm;
use App\Handler\TableHandler;
use DateTime;
use App\Entity\Products;
use App\Form\SupplyForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SupplyController extends AbstractController
{
    /**
     * @Route("supply/{product}", name="show_category", methods={"GET"})
     */
    public function viewCategory(Products $product, Request $request): Response
    {
        $form = $this->createForm(SupplyForm::class, $product);
        $form->handleRequest($request);
        return $this->render('formsadding/addProdSupply.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("supply/{product}", name="update_category", methods={"POST"})
     */
    public function updateCategory(Products $product, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SupplyForm::class, $product);
        $oldCount = $product->getStatusCount();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryForm = $form->getData();
            $categoryForm->setStatusCount($categoryForm);
            $countHist = (new CountHist())
                ->setCount($oldCount)
                ->setDate(new DateTime(date("Y-m-d", time())))
                ->setdelta($request->get('count') - $product->getStatusCount())
                ->setProduct($product);
            $entityManager->persist($countHist);
            $entityManager->flush();
            header("Location: /supply");

        }
        return $this->render('formsadding/addProdSupply.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("supply/upload", name="show_category", methods={"GET"})
     */
    public function viewCategoryUpload(Request $request): Response
    {
        $form = $this->createForm(UploadForm::class);
        $form->handleRequest($request);
        return $this->render('formsadding/uploadFile.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("supply/upload", name="update_category", methods={"POST"})
     */
    public function updateCategoryUpload(TableHandler $handler, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UploadForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('newfile')->getData();
            $handler->updateTable($file, $this->getUser());
        }
        return $this->render('formsadding/uploadFile.html.twig', array(
            'form' => $form->createView()
        ));
    }
}