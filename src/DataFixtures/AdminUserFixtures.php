<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Message;
use App\Entity\Reservation;
use App\Entity\User;
use App\Enum\AddressTypeEnum;
use App\Enum\ReservationStatusEnum;
use App\Enum\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        public UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $userAddress = new Address();
        $userAddress
            ->setLine1('4 avenue Salvador Allende')
            ->setLine2(null)
            ->setZipcode('69100')
            ->setCity('Villeurbanne')
            ->setRegion('Rhone')
            ->setType(AddressTypeEnum::PERSONAL->value)
            ->setCountry('France');

        $user = new User();

        $plaintextPassword = 'password';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );

        $user
            ->setFirstname('Augusta')
            ->setLastname('Sarlin')
            ->setPhoneNumber('0614743706')
            ->setRoles([RoleEnum::ROLE_USER])
            ->setPassword($hashedPassword)
            ->setAddress($userAddress)
            ->setEmail('contact@augustasarlin.com');

        $adminAddress = new Address();
        $adminAddress
            ->setLine1('4 avenue Salvador Allende')
            ->setLine2(null)
            ->setZipcode('69100')
            ->setCity('Villeurbanne')
            ->setRegion('Rhone')
            ->setType(AddressTypeEnum::PERSONAL->value)
            ->setCountry('France');

        $admin = new User();

        $plaintextPassword = 'password';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            $plaintextPassword
        );

        $admin
            ->setFirstname('Maxime')
            ->setLastname('dlr')
            ->setPhoneNumber('0614743706')
            ->setRoles([RoleEnum::ROLE_ADMIN])
            ->setPassword($hashedPassword)
            ->setAddress($adminAddress)
            ->setEmail('contact@maxdlr.com');

        for ($i = 0; $i < 20; $i++) {
            $userReservation = new Reservation();
            $arrivalDate = $faker->dateTimeBetween('- 1 year', '+ 90 days');
            $departureDate = $faker->dateTimeBetween('- 6 months', '+ 180 days');
            $userReservation
                ->setUser($user)
                ->setAdultCount($faker->numberBetween(1, 6))
                ->setChildCount($faker->numberBetween(0, 4))
                ->setPrice($faker->randomFloat(2, 200, 10000))
                ->setArrivalDate($arrivalDate)
                ->setDepartureDate($departureDate)
                ->addLodging($this->getReference('lodging_' . rand(0, AppFixtures::LODGING_COUNT - 1)))
                ->addLodging($this->getReference('lodging_' . rand(0, AppFixtures::LODGING_COUNT - 1)))
                ->addLodging($this->getReference('lodging_' . rand(0, AppFixtures::LODGING_COUNT - 1)))
                ->setReservationStatus($this->getReference('reservationStatus_' . $faker->randomElement(ReservationStatusEnum::cases())->value));

            $userReservation->setReservationNumber($user, $userReservation);
            $this->setReference('augustaReservation_' . $i, $userReservation);

            $manager->persist($userReservation);
        }

        for ($i = 0; $i < 4; $i++) {
            $message = new Message();

            $message
                ->setSubject($faker->sentence())
                ->setContent($faker->paragraph())
                ->setUser($user)
                ->setLodging($faker->randomElement(
                    [
                        null,
                        $this->getReference('lodging_' . rand(1, AppFixtures::LODGING_COUNT - 1))
                    ]
                ))
                ->setReservation($faker->randomElement(
                    [
                        null,
                        $this->getReference('augustaReservation_' . rand(1, 20 - 1))
                    ]
                ));
            $this->setReference('message_' . $i, $message);
            $manager->persist($message);
        }

        $manager->persist($adminAddress);
        $manager->persist($user);
        $manager->persist($admin);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ReservationStatusFixtures::class, LodgingFixtures::class];
    }


}