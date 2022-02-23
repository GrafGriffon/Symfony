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
            $len = rand(3, 7);
            $lastPrice = 0;
            for ($i = 0; $i < $len; $i++) {
                $priceHist = (new PriceHist())
                    ->setCurrentPrice($this->generator->numberBetween(0, 100000))
                    ->setDate($this->generator->dateTimeBetween((-2-($len-$i)).' years', (-1-($len-$i)).' years'))
                    ->setProduct($product);
                if ($lastPrice == 0) {
                    $priceHist->setDelta(0);
                } else {
                    $priceHist->setDelta($priceHist->getCurrentPrice() - $lastPrice);
                }
                $lastPrice = $priceHist->getCurrentPrice();
                $manager->persist($priceHist);
            }
        }
        $manager->flush();
    }
}
