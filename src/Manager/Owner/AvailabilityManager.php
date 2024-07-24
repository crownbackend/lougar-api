<?php

namespace App\Manager\Owner;

use App\Entity\Garage;
use App\Entity\GarageAvailability;

class AvailabilityManager
{
    public function update(Garage $garage, array $data): void
    {
        $garageAvailability = new GarageAvailability();
        $garageAvailability->setGarage($garage);
        $garageAvailability->setStartAt(new \DateTimeImmutable($data['startDate']));
        if(isset($data['endDate'])){
            $garageAvailability->setEndAt(new \DateTimeImmutable($data['endDate']));
        }
        foreach ($data['availability'] as $key => $value) {
            dump($value);
        }
        dd($garage->getAvailability()->count());
    }
}