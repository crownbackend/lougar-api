<?php

namespace App\Manager\Tenant;

use App\Entity\User;
use App\Helpers\GenerateToken;
use App\Helpers\Messages;
use App\Service\Mailer;
use App\Service\StripeApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class TenantManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly GenerateToken $generateToken,
                                private readonly UserPasswordHasherInterface $passwordHasher,
                                private readonly Mailer $mailer, private StripeApi $stripeApi)
    {
    }

    public function create(User $tenant, string $plainPassword): void
    {
        $password = $this->passwordHasher->hashPassword($tenant, $plainPassword);
        $tenant->setRoles([User::ROLES['Locataire']]);
        $tenant->setPassword($password);
        $tenant->setValidationToken($this->generateToken->registerToken());
        $tenant->setCreatedAtValidateToken(new \DateTimeImmutable());
        $this->entityManager->persist($tenant);
        $this->entityManager->flush();
        $this->stripeApi->createCustomer($tenant);
        $this->mailer->send($tenant->getEmail(), Messages::EMAIL_REGISTER_SUBJECT, 'emails/register/confirm.html.twig', ['token' => $tenant->getValidationToken()]);
    }
}