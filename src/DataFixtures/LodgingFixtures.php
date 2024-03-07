<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\LodgingFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LodgingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $lodgingFactory = new LodgingFactory();
        $lodgings = $lodgingFactory->make(AppFixtures::LODGING_COUNT)->generate();

        $y = 1;
        foreach ($lodgings as $lodging) {
            for ($i = 0; $i < $lodging->getCapacity(); $i++) {
                $lodging->addBed($this->getReference('bed_' . rand(1, AppFixtures::BED_COUNT)));
            }
            $lodging->addFile($this->getReference('file_' . rand(1, AppFixtures::FILE_COUNT)));
            $lodging->addReservation($this->getReference('reservation_' . rand(1, AppFixtures::RESERVATION_COUNT)));
            $manager->persist($lodging);
            $this->addReference('lodging_' . $y, $lodging);
            $y++;
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BedFixtures::class,
            FileFixtures::class,
            ReservationFixtures::class
        ];
    }


}
