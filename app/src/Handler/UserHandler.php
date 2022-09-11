<?php

namespace App\Handler;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserHandler
{
    private EntityManagerInterface $entityManager;
    private UserRepository $repository;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(
        EntityManagerInterface       $entityManager,
        UserPasswordEncoderInterface $encoder,
        UserRepository               $repository
    )
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->encoder = $encoder;
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
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'roles' => $user->getRoles(),
        ];
    }

    public function editUser(User $user, $request): void
    {
        $user
            ->setLastName($request->get('lastName'))
            ->setFirstName($request->get('firstName'))
            ->setPhone($request->get('phone'))
            ->setPassword($request->get('password'))
            ->setEmail($request->get('email'))
            ->setRoles($request->get('roles'));
    }
}