<?php

namespace App\Enum;

enum DayOfWeek: string
{
    case Monday = 'Lundi';
    case Tuesday = 'Mardi';
    case Wednesday = 'Mercredi';
    case Thursday = 'Jeudi';
    case Friday = 'Vendredi';
    case Saturday = 'Samedi';
    case Sunday = 'Dimanche';
}