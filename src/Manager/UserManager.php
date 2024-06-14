<?php

namespace App\Manager;

use App\Helpers\Messages;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class UserManager
{
    public function __construct(private UserRepository $repository)
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
}