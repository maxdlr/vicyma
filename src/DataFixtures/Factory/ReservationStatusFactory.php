<?php

namespace App\DataFixtures\Factory;

use App\Entity\ReservationStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory as Faker;

class ReservationStatusFactory extends Factory
{
    public function __construct()
    {
        $this->item = new ReservationStatus();
    }

    public function persist(ObjectManager $manager): void
    {
        $this->distribute(fn(ReservationStatus $reservationStatus) => $manager->persist($reservationStatus));
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
         */ fn(ReservationStatus $reservationStatus) => $this->applyCriteria($criteria));

        return $this;
    }

    private function build(): ReservationStatus
    {
        $faker = Faker::create();
        $reservationStatus = new ReservationStatus();

        $reservationStatus
            ->setName($faker->randomElement(['PENDING', 'CONFIRMED', 'ARCHIVED', 'DELETED']));

        return $reservationStatus;
    }
}