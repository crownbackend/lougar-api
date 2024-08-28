<?php

declare(strict_types=1);

namespace App\Controller\Tenant;

use App\Entity\Reservation;
use App\Manager\Tenant\TenantManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/locataire', name: 'tenant_')]
class TenantController extends AbstractController
{
    public function __construct(private TenantManager $manager, private PaginatorInterface $paginator)
    {
    }

    #[Route('/tableau-de-bord', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(): Response
    {
        return $this->render('tenant/dashboard.html.twig');
    }

    #[Route('/mes-reservations', name: 'my_reservations', methods: ['GET', 'POST'])]
    public function myReservations(Request $request): Response
    {
        $status = $request->query->get('status');
        $query = $this->manager->myReservations($this->getUser(), (int)$status);
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('tenant/my-reservations.html.twig', [
            'reservations' => $pagination,
            'status' => Reservation::STATUS
        ]);
    }

    #[Route('/reservation/{id}/annuler', name: 'cancel_reservation', methods: ['GET', 'POST'])]
    public function cancelReservation(Reservation $reservation): RedirectResponse
    {
        $this->manager->cancel($reservation);
        $this->addFlash('success', 'Réservation annulée avec succès.');
        return $this->redirectToRoute('tenant_my_reservations');
    }
}