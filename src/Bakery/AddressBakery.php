<?php


namespace App\Bakery;

use App\Bakery\config\AbstractBakery;
use App\Bakery\config\AsBakery;
use App\Entity\Address;
use Generator;

#[AsBakery(bakes: Address::class)]
final class AddressBakery extends AbstractBakery
{
    public function build(): Generator
    {
        yield [
            'line1' => $this->faker->streetAddress(),
            'line2' => $this->faker->randomElement([null, $this->faker->streetName()]),
            'zipcode' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'region' => $this->faker->domainName(),
            'country' => $this->faker->country(),
        ];
    }
}

