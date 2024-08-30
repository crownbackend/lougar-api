<?php

namespace App\Helpers;

use App\Entity\Reservation;
use App\Twig\Extension\ReservationInfoExtension;

class Messages
{
    public function __construct(private ReservationInfoExtension $reservationInfoExtension)
    {
    }

    // register
    const string REGISTER_CONFIRM = "Inscription réussie ! Veuillez vérifier votre boîte de réception pour un e-mail de confirmation et suivre les instructions pour activer votre compte.
     N'oubliez pas de vérifier votre dossier de spams si vous ne voyez pas l'e-mail dans votre boîte de réception.";
    const string EMAIL_REGISTER_SUBJECT = "Lougar.fr : Confirmer votre compte";
    const string REGISTER_CONFIRM_SUCCESS = "Compte confirmer avec succées";
    const string USER_NOT_FOUND = "Utilisateur inexistant";

    public function messageCancelTenant(Reservation $reservation): string
    {
        return $reservation->getTenant()->getFirstName() . ' ' . $reservation->getTenant()->getLastName() .
            ' à annuler la réservation prévu le : ' .
            $this->reservationInfoExtension->showReservationInfo($reservation->getInfo()) .
            ' au garage : ' . $reservation->getGarage()->getName();
    }

    public function messageCancelOwner(Reservation $reservation): string
    {
        return 'Le loueur : ' . $reservation->getRenter()->getFirstName() . ' ' . $reservation->getRenter()->getLastName() .
            ' à annuler la réservation prévu le : ' .
            $this->reservationInfoExtension->showReservationInfo($reservation->getInfo()) .
            ' au garage : ' . $reservation->getGarage()->getName();
    }

    public function messageCreateReservation(Reservation $reservation): string
    {
        return 'Une demande de réservation est en attente de confirmation pour le garage : '. $reservation->getGarage()->getName() .
            ' Pour la date du : ' . $this->reservationInfoExtension->showReservationInfo($reservation->getInfo());
    }
}