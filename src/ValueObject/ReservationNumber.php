<?php

namespace App\ValueObject;

use App\Entity\Reservation;
use App\Entity\User;
use function Symfony\Component\String\u;

class ReservationNumber
{
    public static function new(
        User        $user,
        Reservation $reservation
    ): string
    {
        return self::shortenString($user->getFirstname()) .
            self::shortenString($user->getLastname()) . '-' .
            $reservation->getArrivalDate()->format('dm') .
            $reservation->getDepartureDate()->format('dm');
    }

    private static function shortenString(string $string): string
    {
        $shortenedString = u(str_replace(' ', '', $string))->upper();
        if (u($string)->length() > 4) {
            $shortenedString = $shortenedString->truncate(4);
        }
        return $shortenedString;
    }
}