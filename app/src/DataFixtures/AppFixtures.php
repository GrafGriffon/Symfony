<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

abstract class AppFixtures extends Fixture implements OrderedFixtureInterface
{
    protected $generator;

    public function __construct()
    {
        $this->generator = Factory::create("en_EN");
    }

    public function getOrder()
    {
        return (int) (explode('_', static::class)[1] ?? 999);
    }

    public function load(ObjectManager $manager): void
    {

    }
}
