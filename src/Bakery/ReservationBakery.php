<?php

namespace App\Bakery;

use App\Entity\Reservation;
use App\Bakery\config\AbstractBakery;
use App\Bakery\config\AsBakery;
use Symfony\Component\Uid\Uuid;

#[AsBakery(bakes: Reservation::class)]
class ReservationBakery extends AbstractBakery
{
    public function build(): \Generator
    {
        yield [
            'adultCount' => $this->faker->numberBetween(1, 6),
            'childCount' => $this->faker->numberBetween(0, 4),
            'price' => $this->faker->randomElement([null, $this->faker->randomFloat(2, 200, 10000)]),
            'arrivalDate' => $this->faker->dateTimeBetween('+ 1 day', '+ 8 days'),
            'departureDate' => $this->faker->dateTimeBetween('+ 9 days', '+ 20 days'),
            'reservationNumber' => Uuid::v4()
        ];
    }
}