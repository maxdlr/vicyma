<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\LodgingFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        LodgingFactory::make(10)->persist($manager);
        
        $manager->flush();
    }
}
