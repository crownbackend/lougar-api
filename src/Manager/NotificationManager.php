<?php

namespace App\Manager;

use App\Entity\User;
use App\Repository\NotificationRepository;

class NotificationManager
{
    public function __construct(private NotificationRepository $notificationRepository)
    {
    }

    public function index(User $user): array
    {
        return $this->notificationRepository->findByUser($user);
    }
}