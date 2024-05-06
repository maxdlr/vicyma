<?php

namespace App\ValueObject;

use App\Entity\Reservation;
use App\Entity\User;
use DateTimeInterface;
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
            self::shortenDate($reservation->getArrivalDate()) .
            self::shortenDate($reservation->getDepartureDate());
    }

    private static function shortenString(string $string): string
    {
        $shortenedString = '';
        if (u($string)->length() > 4) {
            u($string)->replace(' ', '');
            $shortenedString = u($string)->truncate(4);
        }
        return $shortenedString;
    }

    private static function shortenDate(DateTimeInterface $date): string
    {
        $shortenedDate = $date->format('d-m');
        u($shortenedDate)->replace('-', '');
        return $shortenedDate;
    }
}