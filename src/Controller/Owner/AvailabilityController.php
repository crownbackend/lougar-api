<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use App\Entity\AvailabilityTime;
use App\Entity\Garage;
use App\Entity\GarageAvailability;
use App\Manager\Owner\AvailabilityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/availability', name: 'owner_availability_')]
class AvailabilityController extends AbstractController
{
    public function __construct(private readonly AvailabilityManager $manager)
    {
    }

    #[Route('/update/{id}', name: 'update', methods: ['POST'])]
    public function updateAvailability(Garage $garage, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->manager->update($garage, $data);
        return $this->render('owner/garage/availability.html.twig', [
            'garage' => $garage,
        ]);
    }

    #[Route('/time/delete/{id}', name: 'time_delete', methods: ['POST'])]
    public function deleteAvailabilityTime(AvailabilityTime $availabilityTime): Response
    {
        $garage = $availabilityTime->getGarageAvailability()->getGarage();
        $this->manager->deleteAvailabilityTime($availabilityTime);
        return $this->render('owner/garage/availability-show.html.twig', [
            'garage' => $garage,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['GET'])]
    public function deleteAvailability(GarageAvailability $garageAvailability): RedirectResponse
    {
        $this->manager->deleteAvailability($garageAvailability);
        return $this->redirectToRoute('owner_garage_edit', ['id' => $garageAvailability->getGarage()->getId()]);
    }
}
