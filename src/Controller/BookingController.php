<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Garage;
use App\Manager\BookingManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function second(Garage $garage, string $token): RedirectResponse|Response
    {
        $result = $this->manager->checkData($garage, $token);
        if($result['type'] === 'day') {
            return $this->forward('App\Controller\BookingController::three', [
                'garage' => $garage,
                'data' => $result,
            ]);
        } elseif ($result['type'] === 'hour') {
            return $this->forward('App\Controller\BookingController::three', [
                'garage' => $garage,
                'data' => $result,
            ]);
        } else {
            return $this->redirectToRoute('booking_second', ['garage' => $garage->getId()]);
        }
    }

    #[Route('/{id}/etape-3/verifications', name: 'three')]
    public function three(Garage $garage, array $data): Response
    {
        return $this->render('booking/three.html.twig', [
            'garage' => $garage,
            'data' => $data,
            'now' => new \DateTimeImmutable(),
        ]);
    }

    #[Route('/create-setup-intent', name: 'create_setup_intent')]
    public function createSetupIntent(): Response
    {
        return $this->json($this->manager->createSetupIntent($this->getUser()));
    }

    #[Route('/create-reservation', name: 'create_reservation')]
    public function createReservation(Request $request): JsonResponse
    {
        if ($request->getContent()) {
            $data = json_decode($request->getContent(), true);
        } else {
            $data = $request->request->all();
        }
        $data = $this->manager->createReservation($this->getUser(), $data);
        return $this->json($data);
    }
}
