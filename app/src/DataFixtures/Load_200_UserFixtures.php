<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class Load_200_UserFixtures extends AppFixtures
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $user = (new User())
                ->setEmail($this->generator->email)
                ->setFirstName($this->generator->firstName)
                ->setLastName($this->generator->lastName)
                ->setPassword($this->generator->password)
                ->setRoles(['ROLE_ADMIN'])
                ->setStatus($this->generator->numberBetween(0, 1));
            $manager->persist($user);
        }

        $manager->flush();
    }
}