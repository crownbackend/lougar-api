<?php

declare(strict_types=1);

namespace App\Controller;

use App\Manager\GarageManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/garage', name: 'garage_')]
class GarageController extends AbstractController
{
    public function __construct(private GarageManager $garageManager, private PaginatorInterface $paginator)
    {
    }

    #[Route('/trouver-un-garage', name: 'search')]
    public function search(Request $request): Response
    {
        $text = $request->query->get('text');
        $cityId = $request->query->get('cityId');
        $city = $request->query->get('city');

        $query = $this->garageManager->search($text, $cityId);
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('garage/index.html.twig', [
            'garages' => $pagination,
            'city' => $city,
            'cityId' => $cityId,
            'text' => $text,
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(string $id): Response
    {
        return $this->render('garage/show.html.twig', [
            'garage' => $this->garageManager->show($id),
        ]);
    }
}
