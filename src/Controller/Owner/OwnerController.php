<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/proprietaire', name: 'owner_')]
class OwnerController extends AbstractController
{
    #[Route('/tableau-de-bord', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(): Response
    {
        return $this->render('owner/dashboard.html.twig');
    }
}
