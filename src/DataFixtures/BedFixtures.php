<?php

namespace App\DataFixtures;

use App\Entity\BedType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use ReflectionException;

class BedFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < AppFixtures::BED_TYPE_COUNT; $i++) {
            $bed = new BedType();

            $bed
                ->setHeight($faker->randomElement([190, 180, 200]))
                ->setWidth($faker->randomElement([140, 90, 180, 200]))
                ->setIsExtra($faker->boolean());

            $this->setReference('bed_' . $i, $bed);
            $manager->persist($bed);
        }

        $manager->flush();
    }
}
