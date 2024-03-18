<?php

namespace App\Bakery;

use App\Entity\ReservationStatus;
use App\Bakery\config\AbstractBakery;
use App\Bakery\config\AsBakery;

#[AsBakery(bakes: ReservationStatus::class)]
class ReservationStatusBakery extends AbstractBakery
{
    public function build(): \Generator
    {
        yield [
            'name' => $this->faker->randomElement(['PENDING', 'CONFIRMED', 'ARCHIVED', 'DELETED']),
        ];
    }
}