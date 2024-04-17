<?php

namespace App\ValueObject;

use App\Entity\Reservation;
use App\Entity\User;

class ReservationNumber
{
    public static function new(
        User        $user,
        Reservation $reservation
    ): string
    {
        return $user->getFirstname() . '-' .
            $user->getLastname() . '_' .
            $reservation->getArrivalDate()->format('d-m-Y') . '_' .
            $reservation->getDepartureDate()->format('d-m-Y');
    }
}