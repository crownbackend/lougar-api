<?php

namespace App\Manager\Owner;

use App\Entity\Payment;
use App\Entity\Reservation;
use App\Entity\User;
use App\Helpers\Functions;
use App\Helpers\GenerateToken;
use App\Helpers\Messages;
use App\Repository\ReservationRepository;
use App\Service\Mailer;
use App\Service\StripeApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class OwnerManager
{
    public function __construct(private readonly EntityManagerInterface      $entityManager,
                                private readonly UserPasswordHasherInterface $passwordHasher,
                                private readonly GenerateToken               $generateToken,
                                private readonly Mailer                      $mailer,
                                private readonly ReservationRepository       $reservationRepository,
                                private readonly StripeApi                   $stripeApi,
                                private readonly Functions                   $functions)
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

    public function getReservations(User $user): array
    {
        return $this->reservationRepository->findBy(['renter' => $user]);
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
            $reservation->setStatus($status);
        }

        $reservation->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }
}