<?php

namespace App\DataFixtures;

use App\Entity\CountHist;
use App\Entity\Products;
use Doctrine\Persistence\ObjectManager;

class Load_500_CountHistFixtures extends AppFixtures
{
    public function load(ObjectManager $manager): void
    {
        $products = $manager->getRepository(Products::class)->findAll();
        foreach ($products as $product) {
            for ($i = 0; $i < rand(3, 7); $i++) {
                $countHist = (new CountHist())
                    ->setCount($this->generator->numberBetween(0, 100000))
                    ->setDate($this->generator->dateTimeBetween())
                    ->setdelta(0)
                    ->setProduct($product);
                $manager->persist($countHist);
            }
        }

        $manager->flush();
    }
}