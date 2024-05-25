<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/register')]
    public function index(): Response
    {
        return $this->render('register/index.html.twig');
    }
}
