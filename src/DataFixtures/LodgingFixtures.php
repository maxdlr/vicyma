<?php

namespace App\DataFixtures;

use App\Entity\Lodging;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class LodgingFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < AppFixtures::LODGING_COUNT; $i++) {
            $lodging = new Lodging();

            $lodging
                ->setName($faker->word())
                ->setDescription($faker->paragraph(10, true))
                ->setCapacity($faker->numberBetween(4, 6))
                ->setRoomCount($faker->numberBetween(1, 4))
                ->setSurface($faker->randomFloat(2, 30, 50))
                ->setBathroomCount($faker->numberBetween(1, 3))
                ->setToiletCount($faker->numberBetween(1, 3))
                ->setTvService($faker->boolean(80))
                ->setAirConditioning($faker->boolean(80))
                ->setWasher($faker->boolean(80))
                ->setWaterHeater($faker->boolean(80))
                ->setParking($faker->boolean(80))
                ->setGate($faker->boolean(80))
                ->setAnimalAllowed($faker->boolean(20))
                ->setTerrace($faker->boolean(80))
                ->setTerraceSurface($faker->randomFloat(2, 10, 20))
                ->setFloor($faker->numberBetween(0, 3))
                ->setPriceByNight($faker->randomFloat(2, 130, 150))
                ->addBed($this->getReference('bed_' . rand(1, AppFixtures::BED_COUNT - 1)))
                ->addMedia($this->getReference('media_' . rand(1, AppFixtures::MEDIA_COUNT - 1)))
                ->addReservation($this->getReference('reservation_' . rand(1, AppFixtures::RESERVATION_COUNT - 1)));

            $manager->persist($lodging);
            $this->addReference('lodging_' . $i, $lodging);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BedFixtures::class,
            MediaFixtures::class,
            ReservationFixtures::class
        ];
    }


}
