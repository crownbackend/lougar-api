<?php

declare(strict_types=1);

namespace App\Controller;

use App\Manager\CityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/city', name: 'city_')]
class CityController extends AbstractController
{
    public function __construct(private CityManager $cityManager)
    {
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        if($request->query->get('query')) {
            return $this->json($this->cityManager->search($request->query->get('query')));
        }
        return $this->json('error not found', 400);
    }
}
