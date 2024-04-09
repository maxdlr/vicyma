<?php

namespace App\DataFixtures;

use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class MediaFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < AppFixtures::FILE_COUNT; $i++) {
            $file = new Media();

            $file
                ->setMediaName($faker->word())
                ->setMediaSize($faker->randomFloat(2, 2, 30))
                ->setCreatedOn($faker->dateTimeBetween('- 5 days'));

            $this->setReference('file_' . $i, $file);
            $manager->persist($file);
        }

        $manager->flush();
    }
}
