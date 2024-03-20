<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < AppFixtures::REVIEW_COUNT; $i++) {
            $review = new Review();

            $review
                ->setRate(rand(1, 5))
                ->setComment($faker->sentences(5, true))
                ->setPublishedOn($faker->dateTimeBetween('-2 months'))
                ->setLodging($faker->randomElement(
                    [
                        null,
                        $this->getReference('lodging_' . rand(1, AppFixtures::LODGING_COUNT - 1))
                    ]
                ))
                ->setUser($this->getReference('user_' . rand(1, AppFixtures::USER_COUNT - 1)));
            $this->setReference('review_' . $i, $review);
            $manager->persist($review);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LodgingFixtures::class,
            UserFixtures::class
        ];
    }


}
