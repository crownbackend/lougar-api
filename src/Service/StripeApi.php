<?php

namespace App\Service;

use App\Entity\InfoPayment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentMethod;
use Stripe\SetupIntent;
use Stripe\StripeClient;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StripeApi
{
    public function __construct(private string $secretKey, private LoggerInterface $logger,
                                private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws ApiErrorException
     */
    public function createCustomer(User $user): Customer
    {
        try {
            $stripe = new StripeClient($this->secretKey);
            $name = $user->getFirstname() . ' ' . $user->getLastname();
            $data = $stripe->customers->create([
                'name' => $name,
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),
            ]);
            $infoPayment = new InfoPayment();
            $infoPayment->setCustomerId($data->id);
            $user->setInfoPayment($infoPayment);
            $this->entityManager->persist($infoPayment);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $data;
        } catch (ApiErrorException $e) {
            $this->logger->error($e->getMessage());
            throw new HttpException(400, 'Payment Error');
        }
    }

    /**
     * @throws ApiErrorException
     */
    public function createSetupIntent(User $user): SetupIntent
    {
        $stripe = new StripeClient($this->secretKey);
        if($user->getInfoPayment()) {
            return $stripe->setupIntents->create([
                'customer' => $user->getInfoPayment()->getCustomerId(),
                'automatic_payment_methods' => ['enabled' => true],
            ]);
        } else {
            $data = $this->createCustomer($user);
            return $stripe->setupIntents->create([
                'customer' => $data->id,
                'automatic_payment_methods' => ['enabled' => true],
            ]);
        }
    }

    public function getCard(InfoPayment $infoPayment): PaymentMethod
    {
        $stripe = new StripeClient($this->secretKey);
        $data = $stripe->paymentMethods->retrieve($infoPayment->getPaymentMethod());
        dd($data->card);
    }

//    public function testPay(User $user)
//    {
//        return $stripe->setupIntents->create([
//            'payment_method_types' => ['card'],
//            'customer' => $data->id,
//        ]);
//        $stripe = new \Stripe\StripeClient($this->secretKey);
//        return $stripe->paymentIntents->create([
//            'payment_method_types' => ['card'],
//            'amount' => 100,
//            'currency' => 'eur',
//            'customer' => '',
//            'off_session' => true, // Pour indiquer que le paiement est hors session
//            'confirm' => true,
//            'payment_method' => "",
//        ]);
//    }
}