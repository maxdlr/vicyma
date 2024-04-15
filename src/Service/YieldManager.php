<?php

namespace App\Service;

use App\Entity\Lodging;
use DateTimeInterface;

class YieldManager
{
    public static function calculateReservationPrice(
        DateTimeInterface $arrivalDate,
        DateTimeInterface $departureDate,
        Lodging           $lodging,
        int               $discountRate = null
    ): float
    {
        $numberOfNights = self::calculateNumberOfNights($arrivalDate, $departureDate);
        $pricePerNight = $lodging->getPriceByNight();

        $totalPrice = $pricePerNight * $numberOfNights;
        if ($discountRate !== null) {
            $totalPrice *= ($discountRate / 100);
        }
        return round($totalPrice, 2);
    }

    public static function calculateNumberOfNights(
        DateTimeInterface $arrivalDate,
        DateTimeInterface $departureDate
    ): int
    {
        return $arrivalDate->diff($departureDate)->format('%a');
    }
}