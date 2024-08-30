<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Entity\Notification;
use App\Repository\ReservationRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class NotificationExtension extends AbstractExtension
{
    public function __construct(private ReservationRepository $reservationRepository)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('urlNotification', [$this, 'urlNotification']),
        ];
    }

    public function urlNotification(Notification $notification): string
    {
        if($notification->getType() === 'reservation') {
            $reservation = $this->reservationRepository->findOneBy(['id' => $notification->getRelatedEntityId()]);
        }
        return '';
    }
}
