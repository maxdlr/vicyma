<?php

namespace App\DataFixtures;

use App\Bakery\MessageBakery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use ReflectionException;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $messageFactory = new MessageBakery();
        $messages = $messageFactory->makeMany(AppFixtures::MESSAGE_COUNT)->bake();

        $i = 1;
        foreach ($messages as $message) {
            $message->setUser($this->getReference('user_' . rand(1, AppFixtures::USER_COUNT)));

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
            ReservationFixtures::class,
            UserFixtures::class
        ];
    }


}
