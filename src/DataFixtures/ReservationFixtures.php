<?php

namespace App\DataFixtures;

use App\Bakery\ReservationBakery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use ReflectionException;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $reservationFactory = new ReservationBakery();
        $reservations = $reservationFactory->makeMany(AppFixtures::RESERVATION_COUNT)->bake();

        $i = 1;
        foreach ($reservations as $reservation) {
            $this->setReference('reservation_' . $i, $reservation);
            $reservation->setReservationStatus($this->getReference('reservationStatus_' . $faker->randomElement(ReservationStatusFixtures::STATUS_NAMES)));
            $manager->persist($reservation);
            $i++;
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ReservationStatusFixtures::class
        ];
    }
}
