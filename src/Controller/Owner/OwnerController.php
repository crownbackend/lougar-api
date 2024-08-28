<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use App\Manager\Owner\OwnerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/proprietaire', name: 'owner_')]
class OwnerController extends AbstractController
{
    public function __construct(private OwnerManager $manager)
    {
    }

    #[Route('/tableau-de-bord', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(): Response
    {
        return $this->render('owner/dashboard.html.twig');
    }

    #[Route('/mes-reservations', name: 'my_reservation')]
    public function myReservation(): Response
    {
        return $this->render('owner/my-reservation.html.twig');
    }

    #[Route('/reservations', name: 'get_reservations', methods: ['GET', 'POST'])]
    public function getReservations(): JsonResponse
    {
        dump($this->manager->getReservations($this->getUser()));
        return $this->json(["reservations" => $this->manager->getReservations($this->getUser())], 200, [], ['groups' => 'list_reservation']);
    }
}
