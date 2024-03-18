<?php

namespace App\Bakery;

use App\Entity\Review;
use App\Bakery\config\AbstractBakery;
use App\Bakery\config\AsBakery;

#[AsBakery(bakes: Review::class)]
class ReviewBakery extends AbstractBakery
{
    public function build(): \Generator
    {
        yield [
            'rate' => rand(1, 5),
            'comment' => $this->faker->sentences(5, true),
            'publishedOn' => $this->faker->dateTimeBetween('-2 months'),
        ];
    }
}