<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\MessageFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $messageFactory = new MessageFactory();
        $messages = $messageFactory->make(AppFixtures::MESSAGE_COUNT)->generate();

        $i = 1;
        foreach ($messages as $message) {
            $message
                ->setLodging($faker->randomElement(
                    [
                        null,
                        $this->getReference('lodging_' . rand(1, AppFixtures::LODGING_COUNT))
                    ]
                ))
                ->setReservation($faker->randomElement(
                    [
                        null,
                        $this->getReference('reservation_' . rand(1, AppFixtures::RESERVATION_COUNT))
                    ]
                ));
            $this->setReference('message_' . $i, $message);
            $manager->persist($message);
            $i++;
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LodgingFixtures::class,
            ReservationFixtures::class
        ];
    }


}
