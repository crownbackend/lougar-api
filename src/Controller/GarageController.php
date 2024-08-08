<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Garage;
use App\Manager\GarageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/garage', name: 'garage_')]
class GarageController extends AbstractController
{
    public function __construct(private GarageManager $garageManager)
    {
    }

    #[Route('/')]
    public function index(): Response
    {
        return $this->render('garage/index.html.twig');
    }

    #[Route('/{id}', name: 'show')]
    public function show(string $id): Response
    {
        return $this->render('garage/show.html.twig', [
            'garage' => $this->garageManager->show($id),
        ]);
    }
}
