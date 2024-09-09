<?php

namespace App\Manager\Owner;

use App\Entity\Payment;
use App\Entity\Reservation;
use App\Entity\User;
use App\Event\NotificationEvent;
use App\Helpers\Functions;
use App\Helpers\GenerateToken;
use App\Helpers\Messages;
use App\Repository\ReservationRepository;
use App\Service\Mailer;
use App\Service\StripeApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class OwnerManager
{
    public function __construct(private readonly EntityManagerInterface      $entityManager,
                                private readonly UserPasswordHasherInterface $passwordHasher,
                                private readonly GenerateToken               $generateToken,
                                private readonly Mailer                      $mailer,
                                private readonly ReservationRepository       $reservationRepository,
                                private readonly StripeApi                   $stripeApi,
                                private readonly Functions                   $functions,
                                private readonly EventDispatcherInterface    $eventDispatcher,
                                private readonly Messages                    $messages)
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
        $this->mailer->send($tenant->getEmail(), Messages::EMAIL_REGISTER_SUBJECT, 'emails/register/confirm.html.twig', ['token' => $tenant->getValidationToken()]);
    }

    public function getReservations(User $user, ?int $status = null): array
    {
        return $this->reservationRepository->findByUser($user, $status, "owner");
    }

    public function cancelReservation(Reservation $reservation): void
    {
        $now = new \DateTimeImmutable();
        $newStartAt = $reservation->getStartAt()->modify('-2 hour');
        if($newStartAt < $now ) {
            $reservation->setStatus(Reservation::STATUS['Annuler']);
            $reservation->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();
            $event = new NotificationEvent(
                $reservation->getTenant(),
                $this->messages->messageNotAcceptedReservation($reservation),
                'reservation',
                $reservation->getId(),
            );
            $this->eventDispatcher->dispatch($event, NotificationEvent::NAME);
        }
    }

    public function reservationStatus(Reservation $reservation, int $status): void
    {
        if($status === 2) {
            $price = (float)$reservation->getTotalPrice();
            $priceStripe = $this->functions->transformPrice($price);
            $data = $this->stripeApi->createPayment($reservation->getTenant()->getInfoPayment(), $priceStripe);
            if($data->status == 'succeeded') {
                $payment = new Payment();
                $payment->setAmount($reservation->getTotalPrice());
                $payment->setCommission($reservation->getInfo()['commission']);
                $payment->setStatus($status);
                $payment->setIdStripe($data->id);
                $payment->setPaymentAt(new \DateTimeImmutable());
                $this->entityManager->persist($payment);
                $reservation->setStatus($status);
                $reservation->setPayment($payment);
            }

        } elseif ($status === 3) {
            $reservation->setStatus(Reservation::STATUS['Annuler']);
        }

        $reservation->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        if($status === Reservation::STATUS['Annuler']) {
            $event = new NotificationEvent(
                $reservation->getTenant(),
                $this->messages->messageCancelOwner($reservation),
                'reservation',
                $reservation->getId(),
            );
            $this->eventDispatcher->dispatch($event, NotificationEvent::NAME);
        } elseif ($status === Reservation::STATUS['Confirmer']) {
            $event = new NotificationEvent(
                $reservation->getTenant(),
                $this->messages->messageConfirmReservation($reservation),
                'reservation',
                $reservation->getId()
            );
            $this->eventDispatcher->dispatch($event, NotificationEvent::NAME);
        }
    }
}