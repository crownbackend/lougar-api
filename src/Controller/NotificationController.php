<?php

declare(strict_types=1);

namespace App\Controller;

use App\Manager\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notification', name: 'notification_')]
class NotificationController extends AbstractController
{
    public function __construct(private NotificationManager $manager)
    {
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('notification/index.html.twig', [
            'notifications' => $this->manager->index($this->getUser())
        ]);
    }
}
