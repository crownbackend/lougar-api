<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use App\Entity\Garage;
use App\Form\Garage\GarageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function add(Request $request): Response
    {
        $garage = new Garage();
        $form = $this->createForm(GarageType::class, $garage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $cityForm = $form->get('city');
            $cityId = $cityForm->get('id')->getData();

            dd($cityId, $formData);
        }
        return $this->render("owner/garage/add.html.twig", [
            'form' => $form->createView(),
        ]);
    }
}
