<?php

declare(strict_types=1);

namespace App\Controller\Owner;

use App\Manager\Owner\PaymentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/paiement', name: 'owner_payment_')]
class PaymentController extends AbstractController
{
    public function __construct(private PaymentManager $paymentManager)
    {
    }

    #[Route('/historique', name: 'historique')]
    public function index(): Response
    {
        $data = $this->paymentManager->index($this->getUser());
        return $this->render('owner/payment/index.html.twig', [
            'data' => $data
        ]);
    }
}
