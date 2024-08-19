<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Garage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation', name: 'booking_')]
class BookingController extends AbstractController
{
    #[Route('/{id}/etape-1', name: 'first')]
    public function first(Garage $garage): Response
    {
        return $this->render('booking/first.html.twig', [
            'garage' => $garage,
        ]);
    }
}
