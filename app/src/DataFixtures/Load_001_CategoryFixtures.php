<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

class Load_001_CategoryFixtures extends AppFixtures
{
    public function load(ObjectManager $manager): void
    {
        $categoryMain= (new Category())
        ->setLevel(0)
        ->setTitle("Catalog");
        $manager->persist($categoryMain);
        for($i=0; $i<5; $i++){
            $category= (new Category())
            ->setLevel(1)
            ->setTitle($this->generator->streetName)
            ->setParent($categoryMain);
            $manager=$this->addNode($manager, 2, $category);
            $manager=$this->addNode($manager, 2, $category);
            $manager->persist($category);
        }
        $manager->flush();
    }

    private function addNode($manager, $level, $parent): ObjectManager
    {
        $category= (new Category())
        ->setLevel($level)
        ->setParent($parent)
        ->setTitle($this->generator->streetName);
        $manager->persist($category);
        if ($level!=3){
            $manager=$this->addNode($manager, $level+1, $category);
            $manager=$this->addNode($manager, $level+1, $category);
        }
        return $manager;
    }
}