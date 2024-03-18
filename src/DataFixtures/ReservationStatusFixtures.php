<?php

namespace App\DataFixtures;

use App\Bakery\ReservationStatusBakery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReservationStatusFixtures extends Fixture
{
    public const STATUS_NAMES = [
        'CONFIRMED',
        'ARCHIVED',
        'DELETED',
        'PENDING',
    ];

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $reservationStatusFactory = new ReservationStatusBakery();

        $reservationStatuses = [];
        foreach (self::STATUS_NAMES as $statusName) {
            $reservationStatuses[] = $reservationStatusFactory->makeOne()->withCriteria(['name' => $statusName])->bake();
        }

        foreach ($reservationStatuses as $reservationStatus) {
            $this->setReference('reservationStatus_' . $reservationStatus->getName(), $reservationStatus);
            $manager->persist($reservationStatus);
        }

        $manager->flush();
    }
}
