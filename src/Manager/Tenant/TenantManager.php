<?php

namespace App\Manager\Tenant;

use App\Entity\Reservation;
use App\Entity\User;
use App\Event\NotificationEvent;
use App\Helpers\GenerateToken;
use App\Helpers\Messages;
use App\Repository\ReservationRepository;
use App\Service\Mailer;
use App\Service\StripeApi;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class TenantManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly GenerateToken $generateToken, private readonly Messages $messages,
                                private readonly UserPasswordHasherInterface $passwordHasher,
                                private readonly Mailer $mailer, private readonly StripeApi $stripeApi,
                                private readonly ReservationRepository $reservationRepository,
                                private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function myReservations(User $user, ?int $status = null): Query
    {
        return $this->reservationRepository->findByTenant($user, $status);
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

    public function cancel(Reservation $reservation): void
    {
        $reservation->setStatus(Reservation::STATUS['Annuler']);
        $reservation->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
        $event = new NotificationEvent(
            $reservation->getRenter(),
            $this->messages->messageCancelTenant($reservation),
            'reservation',
            $reservation->getId(),
        );
        $this->eventDispatcher->dispatch($event, NotificationEvent::NAME);
    }
}