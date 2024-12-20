<?php

declare(strict_types=1);

namespace App\Controller\Tenant;

use App\Entity\User;
use App\Form\RegisterType;
use App\Helpers\Messages;
use App\Manager\Tenant\TenantManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/locataire', name: 'tenant_')]
class RegisterTenantController extends AbstractController
{
    public function __construct(private TenantManager $manager)
    {
    }

    #[Route('/inscription', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        $tenant = new User();
        $form = $this->createForm(RegisterType::class, $tenant, ['is_registration' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->create($tenant, $form->get('password')->getData());
            $this->addFlash("success", Messages::REGISTER_CONFIRM);
            return $this->redirectToRoute('app_login');
        }
        return $this->render('tenant/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
