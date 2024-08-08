<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Garage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/garage', name: 'garage_')]
class GarageController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        return $this->render('garage/index.html.twig');
    }

    #[Route('/{id}', name: 'show')]
    public function show(Garage $garage): Response
    {
        return $this->render('garage/show.html.twig', [
            'garage' => $garage,
        ]);
    }
}
