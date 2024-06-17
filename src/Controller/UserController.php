<?php

namespace App\Controller;

use App\Form\TenantUserType;
use App\Form\UserType;
use App\Helpers\Messages;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/utilisateurs', name: 'user_')]
class UserController extends AbstractController
{
    public function __construct(private readonly UserManager $manager)
    {
    }

    #[Route('/confirmation/{token}', name: 'confirm_account', methods: ['GET', 'POST'])]
    public function confirmAccount(string $token): RedirectResponse
    {
        $this->manager->confirmAccount($token);
        $this->addFlash('success', Messages::REGISTER_CONFIRM_SUCCESS);
        return $this->redirectToRoute('app_login');
    }

    #[Route('/profil', name: 'profile', methods: ['GET', 'POST'])]
    public function profile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->edit($user);
            $this->addFlash('success', 'Modification réussi !');
        }
        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}