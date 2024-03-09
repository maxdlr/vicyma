<?php

namespace App\DataFixtures\Factory;

use App\DataFixtures\Factory\Config\Factory;
use App\Entity\Bed;
use Faker\Factory as Faker;

class BedFactory extends Factory
{
    public function __construct()
    {
        static::$item = new Bed();
    }

    public static function build(): Bed
    {
        $faker = Faker::create();
        $bed = new Bed();

        $bed
            ->setHeight($faker->randomElement([190, 180, 200]))
            ->setWidth($faker->randomElement([140, 90, 180, 200]))
            ->setIsExtra($faker->boolean());

        return $bed;
    }
}