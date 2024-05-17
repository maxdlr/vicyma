<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public const BED_TYPE_COUNT = 5;
    public const LODGING_COUNT = 5;
    public const MEDIA_COUNT = 30;
    public const RESERVATION_COUNT = 15;
    public const MESSAGE_COUNT = 30;
    public const REVIEW_COUNT = 15;
    public const USER_COUNT = 20;
    public const ADDRESS_COUNT = 30;

    public function load(ObjectManager $manager): void
    {
    }
}
