<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Enum\ReservationStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < AppFixtures::RESERVATION_COUNT; $i++) {
            $reservation = new Reservation();
            $arrivalDate = $faker->dateTimeBetween('+ 1 day', '+ 8 days');
            $departureDate = $faker->dateTimeBetween('+ 9 days', '+ 20 days');
            $user = $this->getReference('user_' . rand(1, AppFixtures::USER_COUNT - 1));
            $reservation
                ->setAdultCount($faker->numberBetween(1, 4))
                ->setChildCount($faker->numberBetween(0, 4))
                ->setPrice($faker->randomElement([null, $faker->randomFloat(2, 200, 10000)]))
                ->setArrivalDate($arrivalDate)
                ->setDepartureDate($departureDate)
                ->setUser($user)
                ->setReservationStatus($this->getReference('reservationStatus_' . $faker->randomElement(ReservationStatusEnum::cases())->value));
            $this->setReference('reservation_' . $i, $reservation);

            $reservation->setReservationNumber($user, $reservation);

            $manager->persist($reservation);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ReservationStatusFixtures::class,
            UserFixtures::class
        ];
    }
}
