<?php

namespace App\Manager\Tenant;

use App\Entity\User;
use App\Repository\PaymentRepository;

class PaymentManager
{
    public function __construct(private PaymentRepository $paymentRepository)
    {
    }

    public function index(User $user): array
    {
        return $this->paymentRepository->findByUser($user);
    }
}