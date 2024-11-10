<?php

namespace App\Manager\Owner;

use App\Entity\Payment;
use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Wallet;
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

    /**
     * @throws \DateMalformedStringException
     */
    public function getReservations(User $user, ?int $status = null, ?string $startAt = null, ?string $endAt = null): array
    {
        return $this->reservationRepository->findByUser($user, "owner", $status, $startAt, $endAt);
    }

    public function checkExistReservation(User $user, Reservation $newReservation): null|Reservation
    {
        $newStart = $newReservation->getStartAt()->format('Y-m-d');
        $newEnd = $newReservation->getEndAt()->format('Y-m-d');
        $reservationId = $newReservation->getId(); // ID de la réservation en cours

        foreach ($user->getReservations() as $existingReservation) {
            // Ignorer la réservation en cours
            if ($reservationId === $existingReservation->getId()) {
                continue;
            }

            if($existingReservation->getStatus() === Reservation::STATUS['Annuler']) {
                continue;
            }

            if($newReservation->getStatus() === Reservation::STATUS['Confirmer']) {
                continue;
            }

            if($newReservation->getStatus() === Reservation::STATUS['Annuler']) {
                continue;
            }

            $existingStart = $existingReservation->getStartAt()->format('Y-m-d');
            $existingEnd = $existingReservation->getEndAt()->format('Y-m-d');

            // Vérifier le chevauchement des dates uniquement
            if ($newStart <= $existingEnd && $newEnd >= $existingStart) {
                return $existingReservation;
            }
        }

        return null;
    }

    public function cancelReservation(Reservation $reservation): void
    {
        $now = new \DateTimeImmutable();
        $newStartAt = $reservation->getStartAt()->modify('-2 hour');
        if($reservation->getStatus() === Reservation::STATUS['En attente'] && $newStartAt < $now) {
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
            // create payment with stripe
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
                // create wallet and add to balance
                $wallet = new Wallet();
                $wallet->setBalance($reservation->getTotalPrice());
                $wallet->setUsers($reservation->getRenter());
                $this->entityManager->persist($wallet);
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

    public function deleteReservation(Reservation $reservation): void
    {
        $reservation->setDeletedAt(new \DateTimeImmutable());
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }
}