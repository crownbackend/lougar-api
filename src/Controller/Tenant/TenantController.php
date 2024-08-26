<?php

declare(strict_types=1);

namespace App\Controller\Tenant;

use App\Manager\Tenant\TenantManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/locataire', name: 'tenant_')]
class TenantController extends AbstractController
{
    public function __construct(private TenantManager $manager)
    {
    }

    #[Route('/tableau-de-bord', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(): Response
    {
        return $this->render('tenant/dashboard.html.twig');
    }

    #[Route('/mes-reservations', name: 'my_reservations', methods: ['GET', 'POST'])]
    public function myReservations(): Response
    {
        return $this->render('tenant/my-reservations.html.twig', [
            'reservations' => $this->manager->myReservations($this->getUser())
        ]);
    }
}