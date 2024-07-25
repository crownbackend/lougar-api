<?php

namespace App\Manager\Owner;

use App\Entity\AvailabilityTime;
use App\Entity\Garage;
use App\Entity\GarageAvailability;
use Doctrine\ORM\EntityManagerInterface;

class AvailabilityManager
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function update(Garage $garage, array $data): void
    {
        $now = new \DateTimeImmutable();

        if($garage->getAvailability()->first()) {
            $garageAvailability = $garage->getAvailability()->first();
        } else {
            $garageAvailability = new GarageAvailability();
        }

        if(isset($data['startDate'])) {
            $garageAvailability->setStartAt(new \DateTimeImmutable($data['startDate']));
        }
        if(isset($data['endDate'])){
            $garageAvailability->setEndAt(new \DateTimeImmutable($data['endDate']));
        }

        $garageAvailability->setGarage($garage);
        foreach ($data['availability'] as $value) {
            foreach ($value['times'] as $time) {
                if($time['start'] && $time['end']) {
                    $startTime = $time['start'];
                    $endTime = $time['end'];
                    $startDateTime = $now->setTime(...explode(':', $startTime));
                    $endDateTime = $now->setTime(...explode(':', $endTime));
                    $availabilityTime = new AvailabilityTime();
                    $availabilityTime->setStartTime($startDateTime);
                    $availabilityTime->setEndTime($endDateTime);
                    $availabilityTime->setGarageAvailability($garageAvailability);
                    $availabilityTime->setDayOfWeek($value['day']);
                    $this->entityManager->persist($availabilityTime);
                }
           }
        }
        $this->entityManager->persist($garageAvailability);
        $this->entityManager->flush();
    }

    public function deleteAvailabilityTime(AvailabilityTime $availabilityTime): void
    {
        $this->entityManager->remove($availabilityTime);
        $this->entityManager->flush();
    }
}