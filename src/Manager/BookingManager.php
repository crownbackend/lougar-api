<?php

namespace App\Manager;

use App\Entity\Garage;
use App\Entity\Payment;
use App\Entity\User;
use App\Service\StripeApi;
use Stripe\Checkout\Session;
use Stripe\SetupIntent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookingManager
{
    public function __construct(private StripeApi $stripeApi)
    {
    }

    /**
     * @param Garage $garage
     * @param string $token
     * @return array
     * @throws \Exception
     */
    public function checkData(Garage $garage, string $token): array
    {
        $data = json_decode(base64_decode($token), true);
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        if (json_last_error() === JSON_ERROR_NONE) {
            if($data['type'] === 'day') {
                $startDate = new \DateTimeImmutable($data['startDate'], new \DateTimeZone('UTC'));
                $endDate = new \DateTimeImmutable($data['endDate']);
                $priceTaux = $data['priceTaux'];
                $totalPrice = $data['totalPrice'];
                $days = $data['days'];
                $total = (float) $garage->getPricePerDay() * $days;

                // check date valid
                if($startDate <= $now){
                    throw new HttpException(400, 'Invalid date range');
                }
                // check price
                if($priceTaux !== $garage->getPricePerDay()){
                    throw new HttpException(400, 'Invalid price range');
                }
                // check total price
                if($total != $totalPrice){
                    throw new HttpException(400, 'Invalid price range');
                }

                $commission = $this->calculateCommission($totalPrice);

                return [
                    'garage' => $garage,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'priceTaux' => $priceTaux,
                    'totalPrice' => $totalPrice,
                    'days' => $days,
                    'type' => $data['type'],
                    'commission' => $commission,
                    'total' => $totalPrice + $commission,
                ];
            } else if($data['type'] === 'hours') {
                dd($data);
            }
            return [];
        } else {
            throw new HttpException('Invalid token');
        }
    }

    public function createSessionStripe(User $user): string
    {
        $data = $this->stripeApi->createCustomer($user);
//        $data = $this->stripeApi->testPay($user);
//        dump($data);
        return $data->client_secret;
    }

    private function calculateCommission(int $total): int
    {

        return number_format($total * Payment::COMMISSION, 2);
    }
}