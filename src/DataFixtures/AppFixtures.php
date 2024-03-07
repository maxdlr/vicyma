<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\BedFactory;
use App\DataFixtures\Factory\FileFactory;
use App\DataFixtures\Factory\LodgingFactory;
use App\DataFixtures\Factory\ReservationFactory;
use App\DataFixtures\Factory\ReservationStatusFactory;
use App\Repository\BedRepository;
use App\Repository\FileRepository;
use App\Repository\LodgingRepository;
use App\Repository\ReservationRepository;
use App\Repository\ReservationStatusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public const BED_COUNT = 10;
    public const LODGING_COUNT = 10;
    public const FILE_COUNT = 30;
    public const RESERVATION_COUNT = 10;

    public function load(ObjectManager $manager): void
    {
    }
}
