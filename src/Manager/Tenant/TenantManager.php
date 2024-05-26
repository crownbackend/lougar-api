<?php

namespace App\Manager\Tenant;

use App\Entity\User;
use App\Helpers\GenerateToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TenantManager
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private GenerateToken $generateToken,
                                private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function create(User $tenant, string $plainPassword): void
    {
        $password = $this->passwordHasher->hashPassword($tenant, $plainPassword);
        $tenant->setRoles([User::ROLES['Locataire']]);
        $tenant->setPassword($password);
        $tenant->setValidationToken($this->generateToken->registerToken());
        $this->entityManager->persist($tenant);
        $this->entityManager->flush();
    }
}