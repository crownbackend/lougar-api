<?php

namespace App\Manager\Owner;

use App\Entity\User;
use App\Repository\GarageRepository;

class GarageManager
{
    public function __construct(private GarageRepository $garageRepository)
    {
    }

    public function index(User $user): array
    {
        return $this->garageRepository->findBy(['owner' => $user], ['createdAt' => 'DESC']);
    }
}