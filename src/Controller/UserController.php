<?php

namespace App\Controller;

use App\Helpers\Messages;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
}