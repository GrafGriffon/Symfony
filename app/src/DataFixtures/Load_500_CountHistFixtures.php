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
            $len = rand(3, 7);
            $lastCount = 0;
            for ($i = 0; $i < $len; $i++) {
                $countHist = (new CountHist())
                    ->setCount($this->generator->numberBetween(0, 100000))
                    ->setDate($this->generator->dateTimeBetween((-2-($len-$i)).' years', (-1-($len-$i)).' years'))
                    ->setProduct($product);
                if ($lastCount == 0) {
                    $countHist->setDelta(0);
                } else {
                    $countHist->setDelta($countHist->getCount() - $lastCount);
                }
                $lastCount = $countHist->getCount();
                $manager->persist($countHist);
            }
        }

        $manager->flush();
    }
}