<?php

namespace App\DataFixtures;

use App\Entity\ReservationStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class ReservationStatusFixtures extends Fixture
{
    public const STATUS_NAMES = [
        'CONFIRMED',
        'ARCHIVED',
        'DELETED',
        'PENDING',
    ];

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        foreach (self::STATUS_NAMES as $statusName) {
            $reservationStatus = new ReservationStatus();
            $reservationStatus->setName($statusName);
            $this->setReference('reservationStatus_' . $statusName, $reservationStatus);
            $manager->persist($reservationStatus);
        }
        $manager->flush();
    }
}
