<?php

namespace App\DataFixtures;

use App\Controller\Enum\RoleEnum;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create()->unique();

        for ($i = 0; $i < AppFixtures::USER_COUNT; $i++) {
            $user = new User();
            $user
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPhoneNumber($faker->phoneNumber())
                ->setRoles([RoleEnum::ROLE_USER])
                ->setPassword($faker->password())
                ->setAddress($this->getReference('address_' . rand(1, AppFixtures::ADDRESS_COUNT - 1)))
                ->setEmail($faker->email());

            $this->setReference('user_' . $i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [AddressFixtures::class];
    }


}
