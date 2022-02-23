<?php

namespace App\Controller\pages;

use App\Form\ProductForm;
use App\Form\UploadForm;
use App\Handler\TableHandler;
use App\Repository\ProductsRepository;
use App\Repository\SupplyRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Throwable;

class SupplyController extends AbstractController
{
    /**
     * @Route("/supply", methods={"GET"})
     * @IsGranted("ROLE_SUPPLY")
     */
    public function show(Request $request, ProductsRepository $repository, PaginatorInterface $paginator): Response
    {
        try {
            $products = $paginator->paginate(
                $repository->getListProduct($this->getUser()),
                $request->query->getInt('page', 1),
                10
            );

            return $this->render('index/supply.html.twig', [
                'supply' => $this->getUser()->getSupply()->getSupplier(),
                'elements' => $products
            ]);
        }
        catch (Throwable $e){
            return $this->render('error.html.twig');
        }
    }

    /**
     * @Route("/supply", methods={"POST"})
     * @IsGranted("ROLE_SUPPLY")
     */
    public function downlandTable(Request $request,TableHandler $handler, ProductsRepository $repository, PaginatorInterface $paginator): Response
    {
        try {
            $handler->createTable($repository->getListProduct($this->getUser())->getResult(), 'ProductsSupply');
            $handler->downloadTable('ProductsSupply');

            $products = $paginator->paginate(
                $repository->getListProduct($this->getUser()),
                $request->query->getInt('page', 1),
                10
            );

            return $this->render('index/supply.html.twig', [
                'supply' => $this->getUser()->getSupply()->getSupplier(),
                'elements' => $products
            ]);
        }
        catch (Throwable $e){
            return $this->render('error.html.twig');
        }
    }


    /**
     * @Route("/upload", methods={"GET"})
     * @IsGranted("ROLE_SUPPLY")
     */
    public function form(Request $request, SluggerInterface $slugger): Response
    {
        try {
            $form = $this->createForm(UploadForm::class);
            $form->handleRequest($request);

            return $this->render('formsadding/uploadFile.html.twig', [
                'form' => $form->createView()
            ]);
        }
        catch (Throwable $e){
            return $this->render('error.html.twig');
        }
    }

    /**
     * @Route("/upload", methods={"POST"})
     * @IsGranted("ROLE_SUPPLY")
     */
    public function uploadTable(Request $request, SluggerInterface $slugger): Response
    {
        try {
            $form = $this->createForm(UploadForm::class);
            $form = $this->createForm(UploadForm::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $brochureFile = $form->get('brochure')->getData();
                if ($brochureFile) {
                    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $brochureFile->move(
                            $this->getParameter('brochures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }
                }
            }
            return $this->render('formsadding/uploadFile.html.twig', [
                'form' => $form
            ]);
        }
        catch (Throwable $e){
            return $this->render('error.html.twig');
        }
    }
}