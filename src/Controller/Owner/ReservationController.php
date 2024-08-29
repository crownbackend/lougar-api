<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use App\Entity\Reservation;
use App\Manager\Owner\OwnerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/proprietaire/reservation', name: 'owner_reservation_')]
class ReservationController extends AbstractController
{
    public function __construct(private OwnerManager $manager)
    {
    }

    #[Route('/reservation')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig');
    }

    #[Route('/mes-reservations', name: 'my_reservation')]
    public function myReservation(): Response
    {
        return $this->render('owner/my-reservation.html.twig');
    }

    #[Route('/reservations', name: 'get_reservations')]
    public function getReservations(): JsonResponse
    {
        return $this->json(["reservations" => $this->manager->getReservations($this->getUser())], 200, [], ['groups' => 'list_reservation']);
    }

    #[Route('/{id}/sidebar', name: 'reservation_sidebar')]
    public function getReservation(Reservation $reservation): Response
    {
        return $this->render('owner/reservation/reservation-sidebar.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/{status}', name: 'reservation_status')]
    public function reservationStatus(Reservation $reservation, int $status): RedirectResponse
    {
        $this->manager->reservationStatus($reservation, $status);
        return $this->redirectToRoute('owner_reservation_my_reservation');
    }
}
