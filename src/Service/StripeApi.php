<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\SetupIntent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StripeApi
{
    public function __construct(private string $secretKey, private LoggerInterface $logger)
    {
    }

    /**
     * @throws ApiErrorException
     */
    public function createCustomer(User $user): SetupIntent
    {
        try {
            $stripe = new \Stripe\StripeClient($this->secretKey);
            $name = $user->getFirstname() . ' ' . $user->getLastname();
        $data = $stripe->customers->create([
                'name' => $name,
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),
            ]);
            return $stripe->setupIntents->create([
                'payment_method_types' => ['card'],
                'customer' => $data->id,
            ]);
        } catch (ApiErrorException $e) {
            $this->logger->error($e->getMessage());
            throw new HttpException(400, 'Payment Error');
        }
    }

    public function testPay(User $user)
    {
        $stripe = new \Stripe\StripeClient($this->secretKey);
        return $stripe->paymentIntents->create([
            'payment_method_types' => ['card'],
            'amount' => 100,
            'currency' => 'eur',
            'customer' => '',
            'off_session' => true, // Pour indiquer que le paiement est hors session
            'confirm' => true,
            'payment_method' => "",
        ]);
    }
}