<?php

namespace App\Manager;

use App\Entity\Garage;
use App\Entity\Payment;
use App\Entity\Reservation;
use App\Entity\User;
use App\Helpers\Functions;
use App\Repository\GarageRepository;
use App\Service\StripeApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookingManager
{
    public function __construct(private StripeApi $stripeApi, private GarageRepository $garageRepository,
                                private EntityManagerInterface $entityManager, private Functions $functions)
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

                $commission = $this->functions->calculateCommission($totalPrice);

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
            } else if($data['type'] === 'hour') {
                $dateSelect = explode('T', $data['date']);
                $startDate = new \DateTimeImmutable($dateSelect[0]. ' ' .$data["startTime"], new \DateTimeZone('UTC'));
                $endDate = new \DateTimeImmutable($dateSelect[0]. ' ' .$data["endTime"], new \DateTimeZone('UTC'));
                $hours = $this->functions->calculateHours($startDate, $endDate);
                $priceTaux = $data['priceTaux'];
                $totalPrice = $data['totalPrice'];
                $total = (float) $garage->getPricePerHour() * $hours;

                if($startDate <= $now){
                    throw new HttpException(400, 'Invalid date range');
                }
                // check price
                if($priceTaux !== $garage->getPricePerHour()){
                    throw new HttpException(400, 'Invalid price range');
                }
                // check total price
                if($total != $totalPrice){
                    throw new HttpException(400, 'Invalid price range');
                }

                $commission = $this->functions->calculateCommission($totalPrice);

                return [
                    'garage' => $garage,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'priceTaux' => $priceTaux,
                    'totalPrice' => $totalPrice,
                    'hours' => $hours,
                    'total' => $totalPrice + $commission,
                    'type' => $data['type'],
                    'commission' => $commission,
                ];
            }
            return [];
        } else {
            throw new HttpException('Invalid token');
        }
    }

    public function createSetupIntent(User $user): string
    {
        $data = $this->stripeApi->createSetupIntent($user);
        return $data->client_secret;
    }

    public function createReservation(User $user, array $data): string
    {
        $reservationData = json_decode($data['reservationInfo'], true);
        $garage = $this->garageRepository->findOneBy(["id" => $data["garageId"]]);
        $infoPayment = $user->getInfoPayment();
        $infoPayment->setPaymentMethod($data['methodPayment']);
        $reservation = new Reservation();
        $reservation->setGarage($garage);
        $reservation->setRenter($garage->getOwner());
        $reservation->setTenant($user);
        $reservation->setInfo($reservationData);
        $reservation->setStatus(Reservation::STATUS['En attente']);
        $reservation->setTotalPrice($reservationData['total']);
        $reservation->setStartAt(new \DateTimeImmutable($reservationData["startDate"]["date"], new \DateTimeZone('UTC')));
        $reservation->setEndAt(new \DateTimeImmutable($reservationData["endDate"]["date"], new \DateTimeZone('UTC')));
        $this->entityManager->persist($reservation);
        $this->entityManager->persist($infoPayment);
        $this->entityManager->flush();
        return $reservation->getId();
    }
}