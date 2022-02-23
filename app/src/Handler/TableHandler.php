<?php

namespace App\Handler;

use App\Entity\Products;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use App\Repository\SupplyRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class TableHandler
{
    private $repository;
    private $manager;
    private $supplyRepository;
    private $categoryRepository;

    public function __construct(ProductsRepository $repository, EntityManagerInterface $manager,SupplyRepository $supplyRepository, CategoryRepository $categoryRepository)
    {
        $this->repository = $repository;
        $this->manager=$manager;
        $this->supplyRepository=$supplyRepository;
        $this->categoryRepository=$categoryRepository;
    }

    public function createTable(array $products, string $fileName): void
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', "name");
        $sheet->setCellValue('B1', "category");
        $sheet->setCellValue('C1', "count");
        $sheet->setCellValue('D1', "price");
        $counter = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $counter, $product->getName());
            $sheet->setCellValue('B' . $counter, $product->getCategory()->getTitle());
            $sheet->setCellValue('C' . $counter, $product->getStatusCount());
            $sheet->setCellValue('D' . $counter, $product->getCurrPrice());
            $counter++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName . '.xlsx');
    }

    public function downloadTable(string $fileName): void
    {
        header('Content-type: application/octet-stream');
        header('Content-Length: ' . filesize($fileName . '.xlsx'));
        header('Content-Disposition: attachment; filename="Your products.xlsx"');
        readfile($fileName . '.xlsx');
        unlink($fileName . '.xlsx');
    }

    public function updateTable($file, $user)
    {
        copy($file->getPathname(), $file->getClientOriginalName());
        $spreadsheet = IOFactory::load($file->getClientOriginalName());
        $sheet = $spreadsheet->getActiveSheet();
        $counter = 2;
        while ($sheet->getCell('A' . $counter) != '') {
            $productFile = $this->repository->findBy(['name'=>$sheet->getCell('A' . $counter)->getValue()]);
            if(isset($productFile[0])){
                $checkSupply=$this->repository->getProductSupply($user, $sheet->getCell('A' . $counter)->getValue())->getResult();
                if (!isset($checkSupply[0])){
                    $productFile[0]->addSupply(($this->supplyRepository->findBy(['user'=>$user]))[0]);
                }
                $productFile[0]->setStatusCount(($productFile[0]->getStatusCount()+(int)$sheet->getCell('C'.$counter)->getValue()));

            } else {
                $product = (new Products())
                    ->setName($sheet->getCell('A'.$counter)->getValue())
                    ->setStatusCount((int)$sheet->getCell('C'.$counter)->getValue())
                    ->setCurrPrice((int)$sheet->getCell('D'.$counter)->getValue())
                    ->setCategory(($this->categoryRepository->findBy(['title' => $sheet->getCell('B'.$counter)->getValue()]))[0]);
                $this->manager->persist($product);
            }
            $counter++;
        }
        $this->manager->flush();
        unlink($file->getClientOriginalName());
    }
}