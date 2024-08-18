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

    public function search(?string $text = null, ?string $cityId = null, ?string $minPrice = null, ?string $maxPrice = null): Query
    {
        return $this->garageRepository->findBySearch($text, $cityId, $minPrice, $maxPrice);
    }

    public function priceMinMax(): array
    {
        return $this->garageRepository->findMinMaxPrices();
    }

    public function show(string $id): Garage
    {
        return $this->garageRepository->findById($id);
    }
}