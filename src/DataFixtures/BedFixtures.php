<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\BedFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BedFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $beds = BedFactory::make(AppFixtures::BED_COUNT)->generate();

        $i = 1;
        foreach ($beds as $bed) {
            $this->setReference('bed_' . $i, $bed);
            $manager->persist($bed);
            $i++;
        }

        $manager->flush();
    }
}
