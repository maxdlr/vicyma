<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Reservation;
use App\Entity\User;
use App\Enum\AddressTypeEnum;
use App\Enum\ReservationStatusEnum;
use App\Enum\RoleEnum;
use App\ValueObject\ConversationId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SpecificFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(
        public UserPasswordHasherInterface $passwordHasher,
    )
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $address = $this->createAddress();
        $manager->persist($address);

        $user = $this->createUser($address);
        $manager->persist($user);

        $admin = $this->createAdmin($address);
        $manager->persist($admin);

        $this->createAndPersistReservations(20, $user, $manager);
        $messages = $this->createAndPersistMessages(4, $user, $manager, 'augustaReservation');
        $this->createAndPersistConversations(
            2,
            $messages,
            $admin,
            $manager,
        );

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ReservationStatusFixtures::class, LodgingFixtures::class];
    }

    private function createAddress(): Address
    {
        $address = new Address();
        $address
            ->setLine1('4 avenue Salvador Allende')
            ->setLine2(null)
            ->setZipcode('69100')
            ->setCity('Villeurbanne')
            ->setRegion('Rhone')
            ->setType(AddressTypeEnum::PERSONAL->value)
            ->setCountry('France');

        return $address;
    }

    private function createPassword(User $user): string
    {
        $plaintextPassword = 'password';
        return $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
    }

    private function createUser(Address $address): User
    {
        $user = new User();
        $user
            ->setFirstname('Augusta')
            ->setLastname('Sarlin')
            ->setPhoneNumber('0614743706')
            ->setRoles([RoleEnum::ROLE_USER])
            ->setPassword($this->createPassword($user))
            ->setAddress($address)
            ->setEmail('contact@augustasarlin.com');
        return $user;
    }

    private function createAdmin(Address $address): User
    {
        $admin = new User();
        $admin
            ->setFirstname('Maxime')
            ->setLastname('dlr')
            ->setPhoneNumber('0614743706')
            ->setRoles([RoleEnum::ROLE_ADMIN])
            ->setPassword($this->createPassword($admin))
            ->setAddress($address)
            ->setEmail('contact@maxdlr.com');
        return $admin;
    }

    private function createAndPersistReservations(
        int           $number,
        User          $user,
        ObjectManager $manager
    ): void
    {
        for ($i = 0; $i < $number; $i++) {
            $userReservation = new Reservation();
            $arrivalDate = $this->faker->dateTimeBetween('- 1 year', '+ 90 days');
            $departureDate = $this->faker->dateTimeBetween('- 6 months', '+ 180 days');
            $userReservation
                ->setUser($user)
                ->setAdultCount($this->faker->numberBetween(1, 6))
                ->setChildCount($this->faker->numberBetween(0, 4))
                ->setPrice($this->faker->randomFloat(2, 200, 10000))
                ->setArrivalDate($arrivalDate)
                ->setDepartureDate($departureDate)
                ->addLodging($this->getReference('lodging_' . rand(0, AppFixtures::LODGING_COUNT - 1)))
                ->addLodging($this->getReference('lodging_' . rand(0, AppFixtures::LODGING_COUNT - 1)))
                ->addLodging($this->getReference('lodging_' . rand(0, AppFixtures::LODGING_COUNT - 1)))
                ->setReservationStatus($this->getReference('reservationStatus_' . $this->faker->randomElement(ReservationStatusEnum::cases())->value));

            $userReservation->setReservationNumber($user, $userReservation);
            $this->setReference('augustaReservation_' . $i, $userReservation);

            $manager->persist($userReservation);
        }
    }

    private function createAndPersistMessages(
        int           $number,
        User          $user,
        ObjectManager $manager,
        string        $referencePrefix
    ): array
    {
        $messages = [];
        for ($i = 0; $i < $number; $i++) {
            $message = new Message();

            $message
                ->setSubject($this->faker->sentence())
                ->setContent($this->faker->paragraph())
                ->setUser($user)
                ->setLodging($this->faker->randomElement(
                    [
                        null,
                        $this->getReference('lodging_' . rand(1, AppFixtures::LODGING_COUNT - 1))
                    ]
                ))
                ->setReservation($this->faker->randomElement(
                    [
                        null,
                        $this->getReference($referencePrefix . '_' . rand(1, 20 - 1))
                    ]
                ));
            $this->setReference('message_' . $i, $message);
            $manager->persist($message);
            $messages[] = $message;
        }
        return $messages;
    }

    private function createAndPersistConversations(
        int           $numberOfAdminResponse,
        array         $messages,
        User          $admin,
        ObjectManager $manager,
    ): void
    {
        $conversations = [];
        foreach ($messages as $message) {
            assert($message instanceof Message);
            $conversation = new Conversation();
            $conversation
                ->setUser($message->getUser())
                ->setConversationId(ConversationId::new($message))
                ->addMessage($message);
            $manager->persist($conversation);

            $conversations[] = $conversation;
        }

        foreach ($conversations as $conv) {
            for ($i = 0; $i < $numberOfAdminResponse * rand(1, 3); $i++) {
                $adminMessage = new Message();
                $adminMessage
                    ->setUser(null)
                    ->setAdmin($admin)
                    ->setSubject('Response to ' . $conv->getUser()->getFullName())
                    ->setContent($this->faker->paragraph())
                    ->setConversation($conv);
                $manager->persist($adminMessage);
            }
        }
    }


}