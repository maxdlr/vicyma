<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class AddressFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < AppFixtures::ADDRESS_COUNT; $i++) {
            $address = new Address();
            $address
                ->setLine1($faker->streetAddress())
                ->setLine2($faker->randomElement([null, $faker->streetName()]))
                ->setZipcode($faker->postcode())
                ->setCity($faker->city())
                ->setRegion($faker->domainName())
                ->setCountry($faker->country());

            $this->setReference('address_' . $i, $address);
            $manager->persist($address);
        }

        $manager->flush();
    }
}
