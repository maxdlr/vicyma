<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\ReviewFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $reviewFactory = new ReviewFactory();
        $reviews = $reviewFactory->make(AppFixtures::REVIEW_COUNT)->generate();

        $i = 1;
        foreach ($reviews as $review) {
            $review->setLodging($faker->randomElement(
                [
                    null,
                    $this->getReference('lodging_' . rand(1, AppFixtures::LODGING_COUNT))
                ]
            ));
            $this->setReference('review_' . $i, $review);
            $manager->persist($review);
            $i++;
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LodgingFixtures::class,
        ];
    }


}
