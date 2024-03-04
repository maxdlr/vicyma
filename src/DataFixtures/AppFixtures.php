<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\BedFactory;
use App\DataFixtures\Factory\LodgingFactory;
use App\Repository\BedRepository;
use App\Repository\LodgingRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function __construct(
        private readonly LodgingFactory $lodgingFactory,
        private readonly BedFactory $bedFactory,
        private readonly BedRepository $bedRepository,
        private readonly LodgingRepository $lodgingRepository,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->bedFactory->make(10)->generate() as $bed) {
            $manager->persist($bed);
        }

        foreach ($this->lodgingFactory->make(10)->generate() as $lodging) {
            $manager->persist($lodging);
        }

        $manager->flush();
    }
}
