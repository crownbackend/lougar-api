<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/proprietaire', name: 'owner_garage_')]
class GarageController extends AbstractController
{
    #[Route('/mes-garage', name: 'myGarage', methods: ['GET'])]
    public function myGarage(): Response
    {
        return $this->render('owner/garage/index.html.twig');
    }

    #[Route('/ajouter-un-garage', name: 'add', methods: ['GET', 'POST'])]
    public function add(): Response
    {
        return $this->render("owner/garage/add.html.twig");
    }
}
