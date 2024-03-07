<?php

namespace App\DataFixtures\Factory;

use App\DataFixtures\Factory\Config\Factory;
use App\Entity\Lodging;
use Faker\Factory as Faker;

class LodgingFactory extends Factory
{
    public function __construct()
    {
        $this->item = new Lodging();
    }

    public function build(): Lodging
    {
        $faker = Faker::create();
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
}