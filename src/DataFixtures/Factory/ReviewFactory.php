<?php

namespace App\DataFixtures\Factory;

use App\DataFixtures\Factory\Config\Factory;
use App\Entity\Review;
use Faker\Factory as Faker;

class ReviewFactory extends Factory
{
    public function __construct()
    {
        static::$item = new Review();
    }

    public static function build(): Review
    {
        $faker = Faker::create();
        $review = new Review();

        $review
            ->setRate(rand(1, 5))
            ->setComment($faker->sentences(5, true))
            ->setPublishedOn($faker->dateTimeBetween('-2 months'));

        return $review;
    }
}