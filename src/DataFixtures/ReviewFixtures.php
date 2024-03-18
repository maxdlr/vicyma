<?php

namespace App\DataFixtures;

use App\Bakery\ReviewBakery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $reviewFactory = new ReviewBakery();
        $reviews = $reviewFactory->makeMany(AppFixtures::REVIEW_COUNT)->bake();

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
