<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/city', name: 'city_')]
class CityController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([]);
    }
}
