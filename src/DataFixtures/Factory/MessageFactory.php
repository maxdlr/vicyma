<?php

namespace App\DataFixtures\Factory;

use App\DataFixtures\Factory\Config\Factory;
use App\Entity\Message;
use Faker\Factory as Faker;

class MessageFactory extends Factory
{
    public function __construct()
    {
        static::$item = new Message();
    }

    public static function build(): Message
    {
        $faker = Faker::create();
        $message = new Message();

        $message
            ->setSubject($faker->sentence())
            ->setContent($faker->paragraph())
            ->setSentOn($faker->dateTimeBetween('-2 months'));

        return $message;
    }
}