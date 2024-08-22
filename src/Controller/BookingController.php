<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Garage;
use App\Manager\BookingManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation', name: 'booking_')]
class BookingController extends AbstractController
{
    public function __construct(private BookingManager $manager)
    {
    }

    #[Route('/{id}/etape-1', name: 'first')]
    public function first(Garage $garage): Response
    {
        return $this->render('booking/first.html.twig', [
            'garage' => $garage,
        ]);
    }

    #[Route('/{id}/etape-2/{token}', name: 'second')]
    public function second(Garage $garage, string $token): RedirectResponse
    {
        $result = $this->manager->checkData($garage, $token);
        if($result['type'] === 'day') {
            return $this->redirectToRoute('');
        } elseif ($result['type'] === 'hours') {
            return $this->redirectToRoute('');
        } else {
            return $this->redirectToRoute('reservation_first', ['garage' => $garage->getId()]);
        }
    }
}
