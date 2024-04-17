<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < AppFixtures::MESSAGE_COUNT; $i++) {
            $message = new Message();

            $message
                ->setSubject($faker->sentence())
                ->setContent($faker->paragraph())
                ->setUser($this->getReference('user_' . rand(1, AppFixtures::USER_COUNT - 1)))
                ->setLodging($faker->randomElement(
                    [
                        null,
                        $this->getReference('lodging_' . rand(1, AppFixtures::LODGING_COUNT - 1))
                    ]
                ))
                ->setReservation($faker->randomElement(
                    [
                        null,
                        $this->getReference('reservation_' . rand(1, AppFixtures::RESERVATION_COUNT - 1))
                    ]
                ));
            $this->setReference('message_' . $i, $message);
            $manager->persist($message);
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
