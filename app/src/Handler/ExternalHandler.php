<?php

namespace App\Handler;

use App\DTO\ProductDto;
use App\Entity\CountHist;
use App\Entity\Products;
use App\Repository\CategoryRepository;
use App\Repository\ExternalRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


class ExternalHandler
{

    protected $doctrine;
    protected $manager;
    private $repository;
    private $categoryRepository;
    protected $external;

    public function __construct(
        ExternalRepository     $external,
        ProductsRepository     $repository,
        ManagerRegistry        $doctrine,
        EntityManagerInterface $manager,
        CategoryRepository     $categoryRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->manager = $manager;
        $this->doctrine = $doctrine;
        $this->repository = $repository;
        $this->external = $external;
    }

    public function uploadData()
    {
        $results=$this->external->getProducts();
        $counter = 0;
        foreach ($results as $element) {
            $productDto = ProductDto::fromArray($element);
            $current = $this->repository->findOneBy(['article' => $productDto->getArticle()]);
            if ($current) {
                if($current->getDateUpdate()!==$productDto->getDateUpdate()){
                    $current
                        ->setName($productDto->getName())
                        ->setDateCreate($productDto->getDateCreate())
                        ->setDateUpdate($productDto->getDateUpdate())
                        ->setStatusCount($productDto->getCount())
                        ->setCurrPrice($productDto->getPrice());
                }
            } else {
                $product = (new Products())
                    ->setName($productDto->getName())
                    ->setArticle($productDto->getArticle())
                    ->setDateCreate($productDto->getDateCreate())
                    ->setDateUpdate($productDto->getDateUpdate())
                    ->setStatusCount($productDto->getCount())
                    ->setCurrPrice($productDto->getPrice())
                    ->setCategory($this->categoryRepository->findOneBy(['level' => 3]));
                $this->manager->persist($product);
            }
            $counter++;
            if ($counter % 200 == 0) {
                $this->manager->flush();
            }
        }
        $this->manager->flush();
    }
}