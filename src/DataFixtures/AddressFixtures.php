<?php

namespace App\DataFixtures;

use App\Bakery\AddressBakery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AddressFixtures extends Fixture
{
    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $addressFactory = new AddressBakery();
        $addresses = $addressFactory->makeMany(AppFixtures::ADDRESS_COUNT)->bake();

        $i = 1;
        foreach ($addresses as $address) {
            $this->setReference('address_' . $i, $address);
            $manager->persist($address);
            $i++;
        }

        $manager->flush();
    }
}
