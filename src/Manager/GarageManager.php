<?php

namespace App\Manager;

use App\Entity\Garage;
use App\Repository\GarageRepository;

class GarageManager
{
    public function __construct(private GarageRepository $garageRepository)
    {
    }

    public function show(string $id): Garage
    {
        return $this->garageRepository->findById($id);
    }
}