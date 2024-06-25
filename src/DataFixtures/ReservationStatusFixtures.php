<?php

namespace App\DataFixtures;

use App\Entity\ReservationStatus;
use App\Enum\ReservationStatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class ReservationStatusFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (ReservationStatusEnum::cases() as $statusName) {
            $reservationStatus = new ReservationStatus();
            $reservationStatus->setName($statusName->value);
            $reservationStatus->setDescription($faker->sentence(10));
            $this->setReference('reservationStatus_' . $statusName->value, $reservationStatus);
            $manager->persist($reservationStatus);
        }
        $manager->flush();
    }
}
