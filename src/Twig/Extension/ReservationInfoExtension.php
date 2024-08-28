<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ReservationInfoExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ReservationInfoExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('reservation_info', [$this, 'showReservationInfo']),
        ];
    }

    public function showReservationInfo(array $data): string
    {
        $startAt = new \DateTimeImmutable($data['startDate']['date']);
        $endAt = new \DateTimeImmutable($data['endDate']['date']);
        if ($data['type'] === 'hour') {
            return $startAt->format('d/m/y') . ' : ' . $startAt->format('H:i') . ' - ' . $endAt->format('H:i');
        } else if ($data['type'] === 'day') {
            return $startAt->format('d/m/y') . ' - ' . $endAt->format('d/m/y');
        } else {
            return "Erreur d'affichage";
        }
    }
}
