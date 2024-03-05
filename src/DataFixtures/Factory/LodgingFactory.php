<?php

namespace App\DataFixtures\Factory;

use App\Entity\Lodging;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory as Faker;

class LodgingFactory extends Factory
{
    public function __construct()
    {
        $this->item = new Lodging();
    }

    public function persist(ObjectManager $manager): void
    {
        $this->distribute(fn(Lodging $lodging) => $manager->persist($lodging));
    }

    public function make(int $number = 1): self
    {
        if ($number === 1) {
            $this->item = self::build();
        } else {
            $tempCollection = new ArrayCollection();
            for ($i = 0; $i < $number; $i++) {
                $tempCollection->add(self::build());
            }
            $this->item = $tempCollection;
        }
        return $this;
    }

    public function withCriteria(array $criteria): self
    {
        $this->distribute(/**
         * @throws Exception
         */ fn(Lodging $lodging) => $this->applyCriteria($criteria));

        return $this;
    }

    private function build(): Lodging
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
            ->setPriceByNight($faker->randomFloat(2, 130, 150))
        ;

        return $lodging;
    }
}