<?php

namespace App\DataFixtures;

use App\Bakery\UserBakery;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use ReflectionException;

class UserFixtures extends Fixture implements DependentFixtureInterface
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
            $user->setAddress($this->getReference('address_' . rand(1, AppFixtures::ADDRESS_COUNT)));
            $this->setReference('user_' . $i, $user);
            $manager->persist($user);
            $i++;
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [AddressFixtures::class];
    }


}
