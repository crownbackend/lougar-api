<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use App\Entity\User;
use App\Form\RegisterType;
use App\Manager\Owner\OwnerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/proprietaire/', name: 'owner_')]
class RegisterController extends AbstractController
{
    public function __construct(private readonly OwnerManager $manager)
    {
    }

    #[Route('/inscription', name: 'register', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $tenant = new User();
        $form = $this->createForm(RegisterType::class, $tenant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->create($tenant, $form->get('password')->getData());
            return $this->redirectToRoute('app_login');
        }
        return $this->render('owner/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
