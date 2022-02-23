<?php

namespace App\Handler;

use App\Entity\CountHist;
use App\Entity\Products;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;


class AddCountHistoryHandler
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addCountHist(Products $product, int $oldCount): void
    {
        if ($oldCount != $product->getStatusCount()) {
            $countHist = (new CountHist())
                ->setCount($oldCount)
                ->setDate(new DateTime(date("Y-m-d", time())))
                ->setDelta($product->getStatusCount() - $oldCount)
                ->setProduct($product);
            $this->entityManager->persist($countHist);
        }
    }

    public function addCountSupply(Products $product, $newPrice): CountHist
    {
        $countHist = (new CountHist())
            ->setCount($product->getStatusCount())
            ->setDate(new DateTime(date("Y-m-d", time())))
            ->setdelta($newPrice - $product->getStatusCount())
            ->setProduct($product);
        $this->entityManager->persist(
            (new CountHist())
                ->setCount($product->getStatusCount())
                ->setDate(new DateTime(date("Y-m-d", time())))
                ->setdelta($newPrice - $product->getStatusCount())
                ->setProduct($product)
        );
        return $countHist;
    }
}