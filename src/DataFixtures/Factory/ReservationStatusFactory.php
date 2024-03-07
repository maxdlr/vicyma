<?php

namespace App\DataFixtures\Factory;

use App\DataFixtures\Factory\Config\Factory;
use App\Entity\ReservationStatus;
use Faker\Factory as Faker;

class ReservationStatusFactory extends Factory
{
    public function __construct()
    {
        $this->item = new ReservationStatus();
    }

    public function build(): ReservationStatus
    {
        $faker = Faker::create();
        $reservationStatus = new ReservationStatus();

        $reservationStatus
            ->setName($faker->randomElement(['PENDING', 'CONFIRMED', 'ARCHIVED', 'DELETED']));

        return $reservationStatus;
    }
}