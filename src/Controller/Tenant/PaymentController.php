<?php

declare(strict_types=1);

namespace App\Controller\Tenant;

use App\Manager\Tenant\PaymentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/paiement/locataire', name: 'payment_tenant_')]
class PaymentController extends AbstractController
{
    public function __construct(private PaymentManager $manager)
    {
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('tenant/payment/index.html.twig', [
            "payments" => $this->manager->index($this->getUser())
        ]);
    }
}
