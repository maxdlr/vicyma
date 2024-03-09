<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\ReservationStatusFactory;
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

    public function load(ObjectManager $manager): void
    {

//        dd(ReservationStatusFactory::make()->withCriteria(['name' => self::STATUS_NAMES[0]])->generate());
        $reservationStatuses = [];
        foreach (self::STATUS_NAMES as $statusName) {
            $reservationStatuses[] = ReservationStatusFactory::make()->withCriteria(['name' => $statusName])->generate();
        }
//        dd($reservationStatuses);

        foreach ($reservationStatuses as $reservationStatus) {
            $this->setReference('reservationStatus_' . $reservationStatus->getName(), $reservationStatus);
            $manager->persist($reservationStatus);
        }

        $manager->flush();
    }
}
