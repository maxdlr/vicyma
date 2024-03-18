<?php

namespace App\DataFixtures;

use App\Bakery\BedBakery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use ReflectionException;

class BedFixtures extends Fixture
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $bedFactory = new BedBakery();
        $beds = $bedFactory->makeMany(AppFixtures::BED_COUNT)->bake();

        $i = 1;
        foreach ($beds as $bed) {
            $this->setReference('bed_' . $i, $bed);
            $manager->persist($bed);
            $i++;
        }

        $manager->flush();
    }
}
