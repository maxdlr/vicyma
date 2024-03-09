<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\ReservationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $reservations = ReservationFactory::make(AppFixtures::RESERVATION_COUNT)->generate();

        $i = 1;
        foreach ($reservations as $reservation) {
            $this->setReference('reservation_' . $i, $reservation);
            $reservation->setReservationStatus($this->getReference('reservationStatus_' . $faker->randomElement(ReservationStatusFixtures::STATUS_NAMES)));
            $manager->persist($reservation);
            $i++;
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ReservationStatusFixtures::class
        ];
    }
}
