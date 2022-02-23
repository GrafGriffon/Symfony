<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Products;
use Doctrine\Persistence\ObjectManager;

class Load_100_ProductsFixures extends AppFixtures
{
    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findBy(['level' => 3]);
        $newCategory = [];
        foreach ($categories as $category) {
            $newCategory[] = $category;
        }
        for ($i = 0; $i < 100; $i++) {
            $product = (new Products())
                ->setName($this->generator->domainName)
                ->setStatusCount($this->generator->numberBetween())
                ->setCurrPrice($this->generator->numberBetween())
                ->setCategory($newCategory[rand(1, count($newCategory)) - 1]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}