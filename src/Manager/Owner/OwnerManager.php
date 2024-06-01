<?php

namespace App\Manager\Owner;

use App\Entity\User;
use App\Helpers\GenerateToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OwnerManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly UserPasswordHasherInterface $passwordHasher,
                                private readonly GenerateToken $generateToken)
    {
    }

    public function create(User $tenant, string $plainPassword): void
    {
        $password = $this->passwordHasher->hashPassword($tenant, $plainPassword);
        $tenant->setRoles([User::ROLES['Propriétaire']]);
        $tenant->setPassword($password);
        $tenant->setValidationToken($this->generateToken->registerToken());
        $tenant->setCreatedAtValidateToken(new \DateTimeImmutable());
        $this->entityManager->persist($tenant);
        $this->entityManager->flush();
    }
}