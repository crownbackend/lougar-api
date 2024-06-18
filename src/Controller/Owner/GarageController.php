<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/proprietaire', name: 'owner_')]
class GarageController extends AbstractController
{
    #[Route('/mes-garage', name: 'myGarage', methods: ['GET', 'POST'])]
    public function myGarage(): Response
    {
        return $this->render('owner/garage/index.html.twig');
    }
}
