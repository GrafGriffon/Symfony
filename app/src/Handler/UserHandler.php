<?php

namespace App\Handler;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserHandler
{
    private EntityManagerInterface $entityManager;
    private UserRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository         $repository
    )
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function getListUsers(): array
    {
        $data = [];
        foreach ($this->repository->findAll() as $user) {
            $data[] = [
                'fullName' => $user->getFirstName() . ' ' . $user->getLastName(),
                'phone' => $user->getPassword(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ];
        }
        return $data;
    }

    public function getUserProfile(User $user): array
    {
        return [
            'id'=>$user->getId(),
            'email'=>$user->getEmail(),
            'phone'=>$user->getPhone(),
            'firstName'=>$user->getFirstName(),
            'lastName'=>$user->getLastName(),
            'roles'=>$user->getRoles(),
        ];
    }
}