<?php

namespace App\DataFixtures\Factory;

use App\Entity\Reservation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory as Faker;

class ReservationFactory extends Factory
{
    public function __construct()
    {
        $this->item = new Reservation();
    }

    public function persist(ObjectManager $manager): void
    {
        $this->distribute(fn(Reservation $reservation) => $manager->persist($reservation));
    }

    public function make(int $number = 1): self
    {
        if ($number === 1) {
            $this->item = self::build();
        } else {
            $tempCollection = new ArrayCollection();
            for ($i = 0; $i < $number; $i++) {
                $tempCollection->add(self::build());
            }
            $this->item = $tempCollection;
        }
        return $this;
    }

    public function withCriteria(array $criteria): self
    {
        $this->distribute(/**
         * @throws Exception
         */ fn(Reservation $reservation) => $this->applyCriteria($criteria));

        return $this;
    }

    private function build(): Reservation
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