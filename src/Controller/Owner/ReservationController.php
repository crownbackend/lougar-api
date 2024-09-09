<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use App\Entity\Reservation;
use App\Manager\Owner\OwnerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/proprietaire/reservation', name: 'owner_reservation_')]
class ReservationController extends AbstractController
{
    public function __construct(private OwnerManager $manager)
    {
    }

    #[Route('/{id}/info', name: 'show')]
    public function show(Reservation $reservation): Response
    {
        if($reservation->getRenter() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $this->manager->cancelReservation($reservation);
        return $this->render('owner/reservation/reservation-show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/mes-reservations', name: 'my_reservation')]
    public function myReservation(): Response
    {
        return $this->render('owner/my-reservation.html.twig');
    }

    #[Route('/reservations', name: 'get_reservations')]
    public function getReservations( #[MapQueryParameter] int $status = null): JsonResponse
    {
        return $this->json(["reservations" => $this->manager->getReservations($this->getUser(), $status)], 200, [], ['groups' => 'list_reservation']);
    }

    #[Route('/{id}/{status}', name: 'reservation_status')]
    public function reservationStatus(Reservation $reservation, int $status): RedirectResponse
    {
        $this->manager->reservationStatus($reservation, $status);
        return $this->redirectToRoute('owner_reservation_my_reservation');
    }
}
