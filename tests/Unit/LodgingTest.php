<?php

namespace App\Tests\Unit;

use App\DataFixtures\Factory\LodgingFactory;
use Exception;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TypeError;

class LodgingTest extends KernelTestCase
{
    /**
     * @throws Exception
     */
    public function testCannotCreateLodgingWithNullProperties()
    {
        $faker = Factory::create();

        $propertyToNullify = $faker->randomElement([
            "Name",
            "Description",
            "Capacity",
            "RoomCount",
            "Surface",
            "BathroomCount",
            "ToiletCount",
            "TvService",
            "Washer",
            "WaterHeater",
            "Parking",
            "Gate",
            "AllowAnimals",
            "Terrace",
            "TerraceSurface",
            "Floor",
            "PriceByNight"
        ]);

        $propertyToNullify != 'TerraceSurface' ?
            $this->expectException(TypeError::class)
            : $this->expectNotToPerformAssertions();

        LodgingFactory::make()->withNull($propertyToNullify)->generate();
    }
}