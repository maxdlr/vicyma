<?php

namespace App\DataFixtures\Factory;

use App\DataFixtures\Factory\Config\Factory;
use App\Entity\File;
use Faker\Factory as Faker;

class FileFactory extends Factory
{
    public function __construct()
    {
        static::$item = new File();
    }

    public static function build(): File
    {
        $faker = Faker::create();
        $file = new File();

        $file
            ->setFileName($faker->word())
            ->setFileSize($faker->randomFloat(2, 2, 30))
            ->setCreatedOn($faker->dateTimeBetween('- 5 days'));

        return $file;
    }
}