<?php

namespace App\Manager;

use App\Entity\User;
use App\Helpers\Messages;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class UserManager
{
    public function __construct(private UserRepository $repository, private EntityManagerInterface $entityManager)
    {
    }

    public function confirmAccount(string $token): void
    {
        $user = $this->repository->findOneBy(['validationToken' => $token]);
        if (null === $user) {
            throw new NotFoundHttpException(Messages::USER_NOT_FOUND);
        }
        // TODO : check createdAtToken
        $user->setValidationToken(null);
        $user->setIsActif(true);
        $this->repository->save($user);
    }

    public function edit(User $tenant): void
    {
        $this->entityManager->persist($tenant);
        $this->entityManager->flush();
    }
}