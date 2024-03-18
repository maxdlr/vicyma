<?php

namespace App\Bakery;

use App\Entity\Lodging;
use App\Bakery\config\AbstractBakery;
use App\Bakery\config\AsBakery;

#[AsBakery(bakes: Lodging::class)]
class LodgingBakery extends AbstractBakery
{
    public function build(): \Generator
    {
        yield [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(10, true),
            'capacity' => $this->faker->numberBetween(1, 6),
            'roomCount' => $this->faker->numberBetween(1, 4),
            'surface' => $this->faker->randomFloat(2, 30, 50),
            'bathroomCount' => $this->faker->numberBetween(1, 3),
            'toiletCount' => $this->faker->numberBetween(1, 3),
            'tvService' => $this->faker->boolean(80),
            'washer' => $this->faker->boolean(80),
            'waterHeater' => $this->faker->boolean(80),
            'parking' => $this->faker->boolean(80),
            'gate' => $this->faker->boolean(80),
            'animalAllowed' => $this->faker->boolean(20),
            'terrace' => $this->faker->boolean(80),
            'terraceSurface' => $this->faker->randomFloat(2, 10, 20),
            'floor' => $this->faker->numberBetween(0, 3),
            'priceByNight' => $this->faker->randomFloat(2, 130, 150),
        ];
    }
}