<?php

namespace App\Helpers;

use App\Entity\Payment;

class Functions
{
    public function replaceMinioUrlWithLocalhost(string $url) {
        // Remplacer l'hôte et le port par "localhost"
        $newUrl = preg_replace('/http:\/\/minio:9000/', 'http://localhost:9000', $url);
        return $newUrl;
    }

    public function calculateCommission(int $total): int
    {
        return number_format($total * Payment::COMMISSION, 2);
    }

    public function calculateHours(\DateTimeImmutable $start, \DateTimeImmutable $end): int|float
    {
        $interval = $start->diff($end);

        $hours = $interval->h + ($interval->days * 24);

        $minutes = $interval->i;
        $hours += $minutes / 60;

        return $hours;
    }
}