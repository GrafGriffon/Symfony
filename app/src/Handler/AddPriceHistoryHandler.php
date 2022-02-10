<?php

namespace App\Handler;

use App\Entity\PriceHist;
use App\Entity\Products;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;


class AddPriceHistoryHandler
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addProductHist(Products $product, int $oldPrice): void
    {
        if ($oldPrice != $product->getCurrPrice()) {
            $priceHist = (new PriceHist())
                ->setCurrentPrice($oldPrice)
                ->setDate(new DateTime(date("Y-m-d", time())))
                ->setdelta($product->getCurrPrice() - $oldPrice)
                ->setProduct($product);
            $this->entityManager->persist($priceHist);
        }
    }
}