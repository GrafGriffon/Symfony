<?php

namespace App\DataFixtures;

use App\Entity\PriceHist;
use App\Entity\Products;
use App\Entity\Supply;
use Doctrine\Persistence\ObjectManager;

class Load_600_SupplyFixtures extends AppFixtures
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $supply= (new Supply())
            ->setSupplier($this->generator->colorName.$this->generator->firstName)
            ->setPeriodOfExecution($this->generator->numberBetween(0, 30));
            $products=$manager->getRepository(Products::class)->findAll();
            foreach ($products as $product){
                if (rand(0,3)==1) {
                    $supply->addProduct($product);
                }
            }
            $manager->persist($supply);
        }
        $manager->flush();
    }
}