<?php

namespace App\Manager;

use App\Repository\CityRepository;

class CityManager
{
    public function __construct(private readonly CityRepository $repository)
    {
    }

    public function search(string $query): array
    {
        return $this->repository->findByName($query);
    }
}