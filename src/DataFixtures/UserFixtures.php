<?php

namespace App\DataFixtures;

use App\Controller\Enum\RoleEnum;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        public UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create()->unique();
        for ($i = 0; $i < AppFixtures::USER_COUNT; $i++) {
            $user = new User();
            $plaintextPassword = $faker->password();

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );

            $user
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPhoneNumber($faker->phoneNumber())
                ->setRoles([RoleEnum::ROLE_USER])
                ->setPassword($hashedPassword)
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
