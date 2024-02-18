<?php

namespace App\DataFixtures\Factory;

use App\Entity\Lodging;
use App\Service\ClassBrowser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class LodgingFactory
{
    static private ArrayCollection|Lodging $lodging;

    static public function generate(): Lodging|ArrayCollection
    {
        return self::$lodging;
    }

    static public function persist(ObjectManager $manager): void
    {
        if (self::$lodging instanceof Lodging) {
            $manager->persist(self::$lodging);
        } else {
            foreach (self::$lodging as $lodging) {
                $manager->persist($lodging);
            }
        }
    }

    static public function make(int $number = 1): static
    {
        if ($number === 1) {
            self::$lodging = self::buildLodging();
        } else {
            $tempCollection = new ArrayCollection();
            for ($i = 0; $i < $number; $i++) {
                $tempCollection->add(self::buildLodging());
            }
            self::$lodging = $tempCollection;
        }
        return new static;
    }

    /**
     * @throws Exception
     */
    static public function withNull(string $fieldName = null): static
    {
        $choices = implode(', ', self::getFields());

        if ($fieldName === null) {
            throw new Exception(
                'Choose a field to make null.' . $choices
            );
        }

        $setter = ClassBrowser::findSetter(Lodging::class, $fieldName);
        self::$lodging->$setter(null);
        return new static;
    }

    static public function buildLodging(): Lodging
    {
        $faker = Factory::create();
        $lodging = new Lodging();

        $lodging
            ->setName($faker->word())
            ->setDescription($faker->paragraph(10, true))
            ->setCapacity($faker->numberBetween(1, 6))
            ->setRoomCount($faker->numberBetween(1, 4))
            ->setSurface($faker->randomFloat(2, 30, 50))
            ->setBathroomCount($faker->numberBetween(1, 3))
            ->setToiletCount($faker->numberBetween(1, 3))
            ->setTvService($faker->boolean(80))
            ->setWasher($faker->boolean(80))
            ->setWaterHeater($faker->boolean(80))
            ->setParking($faker->boolean(80))
            ->setGate($faker->boolean(80))
            ->setAllowAnimals($faker->boolean(20))
            ->setTerrace($faker->boolean(80))
            ->setTerraceSurface($faker->randomFloat(2, 10, 20))
            ->setFloor($faker->numberBetween(0, 3))
            ->setPriceByNight($faker->randomFloat(2, 130, 150));

        return $lodging;
    }

    static private function getFields(): array
    {
        return [
            "Name",
            "Description",
            "Capacity",
            "RoomCount",
            "Surface",
            "BathroomCount",
            "ToiletCount",
            "TvService",
            "Washer",
            "WaterHeater",
            "Parking",
            "Gate",
            "AllowAnimals",
            "Terrace",
            "TerraceSurface",
            "Floor",
            "PriceByNight"
        ];
    }
}