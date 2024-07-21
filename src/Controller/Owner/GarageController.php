<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use App\Entity\Garage;
use App\Form\Garage\GarageType;
use App\Manager\Owner\GarageManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/proprietaire', name: 'owner_garage_')]
class GarageController extends AbstractController
{
    public function __construct(private GarageManager $manager, private PaginatorInterface $paginator)
    {
    }

    #[Route('/mes-garage', name: 'myGarage', methods: ['GET'])]
    public function myGarage(Request $request): Response
    {
        $query = $this->manager->index($this->getUser());
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('owner/garage/index.html.twig', [
            'garages' => $pagination
        ]);
    }

    #[Route('/ajouter-un-garage', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $garage = new Garage();
        $form = $this->createForm(GarageType::class, $garage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cityForm = $form->get('city');
            $cityId = $cityForm->get('id')->getData();
            $this->manager->create($garage, $this->getUser(), $cityId, $form, $form->get('defaultImage')->getData());
            return $this->redirectToRoute('owner_garage_myGarage');
        }
        return $this->render("owner/garage/add.html.twig", [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edition', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request): Response
    {
        $garage = $this->manager->show($id);
        if($garage->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(GarageType::class, $garage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cityForm = $form->get('city');
            $cityId = $cityForm->get('id')->getData();

            $this->manager->edit($garage, $form, $cityId, $form->get('defaultImage')->getData());
            return $this->redirectToRoute('owner_garage_myGarage');
        }
        return $this->render('owner/garage/edit.html.twig', [
            'form' => $form->createView(),
            'garage' => $garage,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', methods: ['GET', 'DELETE'])]
    public function delete(Garage $garage): RedirectResponse
    {
        if($garage->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $this->manager->delete($garage);
        return $this->redirectToRoute('owner_garage_myGarage');
    }
}
