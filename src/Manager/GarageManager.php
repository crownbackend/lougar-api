<?php

namespace App\Manager;

use App\Entity\Garage;
use App\Repository\GarageRepository;
use Doctrine\ORM\Query;

class GarageManager
{
    public function __construct(private GarageRepository $garageRepository)
    {
    }

    public function search(?string $text = null, ?string $cityId = null, ?int $minPrice = null, ?int $maxPrice = null): Query
    {
        return $this->garageRepository->findBySearch($text, $cityId, $minPrice, $maxPrice);
    }

    public function show(string $id): Garage
    {
        return $this->garageRepository->findById($id);
    }
}