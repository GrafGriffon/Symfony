<?php

namespace App\DataFixtures;

use App\Entity\PriceHist;
use App\Entity\Products;
use Doctrine\Persistence\ObjectManager;

class Load_400_PriceHistFixtures extends AppFixtures
{
    public function load(ObjectManager $manager): void
    {
        $products = $manager->getRepository(Products::class)->findAll();
        foreach ($products as $product) {
            for ($i = 0; $i < rand(3, 7); $i++) {
                $priceHist = (new PriceHist())
                    ->setCurrentPrice($this->generator->numberBetween(0, 100000))
                    ->setDate($this->generator->dateTimeBetween())
                    ->setdelta(0)
                    ->setProduct($product);
                $manager->persist($priceHist);
            }
        }
        $manager->flush();
    }
}
