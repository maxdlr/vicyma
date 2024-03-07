<?php

namespace App\DataFixtures\Factory;

use App\DataFixtures\Factory\Config\Factory;
use App\Entity\Reservation;
use Faker\Factory as Faker;

class ReservationFactory extends Factory
{
    public function __construct()
    {
        $this->item = new Reservation();
    }

    public function build(): Reservation
    {
        $faker = Faker::create();
        $reservation = new Reservation();

        $reservation
            ->setAdultCount($faker->numberBetween(1, 6))
            ->setChildCount($faker->numberBetween(0, 4))
            ->setPrice($faker->randomElement([null, $faker->randomFloat(2, 200, 10000)]))
            ->setArrivalDate($faker->dateTimeBetween('+ 1 day', '+ 8 days'))
            ->setDepartureDate($faker->dateTimeBetween('+ 9 days', '+ 20 days'));

        return $reservation;
    }
}