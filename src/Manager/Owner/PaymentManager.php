<?php

namespace App\Manager\Owner;

use App\Entity\User;
use App\Repository\PaymentRepository;
use App\Repository\WalletRepository;

class PaymentManager
{
    public function __construct(private PaymentRepository $paymentRepository, private WalletRepository $walletRepository)
    {
    }

    public function index(User $user): array
    {
        return [
            'wallet' => $user->getWallet() ? $user->getWallet()?->getBalance() : null,
            'payments' => $this->paymentRepository->findByUser($user)
        ];
    }
}