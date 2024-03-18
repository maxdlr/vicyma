<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public const BED_COUNT = 10;
    public const LODGING_COUNT = 10;
    public const FILE_COUNT = 30;
    public const RESERVATION_COUNT = 10;
    public const MESSAGE_COUNT = 30;
    public const REVIEW_COUNT = 15;
    public const USER_COUNT = 20;

    public function load(ObjectManager $manager): void
    {
    }
}
