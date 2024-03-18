<?php

namespace App\Bakery;

use App\Entity\Bed;
use App\Bakery\config\AbstractBakery;
use App\Bakery\config\AsBakery;
use Generator;

#[AsBakery(bakes: Bed::class)]
class BedBakery extends AbstractBakery
{
    public function build(): Generator
    {
        yield [
            'height' => $this->faker->randomElement([190, 180, 200]),
            'width' => $this->faker->randomElement([140, 90, 180, 200]),
            'isExtra' => $this->faker->boolean(),
        ];
    }
}