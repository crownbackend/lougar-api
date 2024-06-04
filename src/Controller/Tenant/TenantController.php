<?php

namespace App\Controller\Tenant;

use App\Form\Tenant\TenantUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/locataire', name: 'tenant_')]
class TenantController extends AbstractController
{
    #[Route('/profil', name: 'profile', methods: ['GET', 'POST'])]
    public function profile(Request $request): Response
    {
        $tenant = $this->getUser();
        $form = $this->createForm(TenantUserType::class, $tenant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }
        return $this->render('tenant/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tableau-de-bord', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(): Response
    {
        return $this->render('tenant/dashboard.html.twig');
    }
}