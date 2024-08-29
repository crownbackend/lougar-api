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

    public function transformPrice(float $price): int
    {
        return (int) round($price * 100);
    }

    public function calculateCommission(int $total): float
    {
        $commission = $total * Payment::COMMISSION;
        // Calcul des frais Stripe (1.4% + 0.25 EUR)
        $stripeFees = ($total * 0.014) + 0.25;
        // Total de la commission avec les frais de Stripe
        $totalCommission = $commission + $stripeFees;
        // Retourner le résultat arrondi à 2 décimales
        return round($totalCommission, 2);
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