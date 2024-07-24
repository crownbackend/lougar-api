<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use App\Entity\Garage;
use App\Manager\Owner\AvailabilityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/availability', name: 'owner_availability')]
class AvailabilityController extends AbstractController
{
    public function __construct(private readonly AvailabilityManager $manager)
    {
    }

    #[Route('/update/{id}', name: 'update', methods: ['POST'])]
    public function update(Garage $garage, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $this->manager->update($garage, $data);
        return $this->json('ok');
    }
}
