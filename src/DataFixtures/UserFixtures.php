<?php

namespace App\DataFixtures;

use App\Bakery\UserBakery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use ReflectionException;

class UserFixtures extends Fixture
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $userFactory = new UserBakery();
        $users = $userFactory->makeMany(AppFixtures::USER_COUNT)->bake();

        $i = 1;
        foreach ($users as $user) {
            $this->setReference('user_' . $i, $user);
            $manager->persist($user);
            $i++;
        }

        $manager->flush();
    }
}
