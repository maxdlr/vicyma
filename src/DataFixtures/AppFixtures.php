<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\BedFactory;
use App\DataFixtures\Factory\FileFactory;
use App\DataFixtures\Factory\LodgingFactory;
use App\DataFixtures\Factory\ReservationFactory;
use App\DataFixtures\Factory\ReservationStatusFactory;
use App\Repository\BedRepository;
use App\Repository\FileRepository;
use App\Repository\LodgingRepository;
use App\Repository\ReservationRepository;
use App\Repository\ReservationStatusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function __construct(
        private readonly LodgingFactory $lodgingFactory,
        private readonly BedFactory $bedFactory,
        private readonly FileFactory $fileFactory,
        private readonly ReservationFactory $reservationFactory,
        private readonly ReservationStatusFactory $reservationStatusFactory,
        private readonly BedRepository $bedRepository,
        private readonly LodgingRepository $lodgingRepository,
        private readonly FileRepository $fileRepository,
        private readonly ReservationRepository $reservationRepository,
        private readonly ReservationStatusRepository $reservationStatusRepository,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->mountObjects($manager);
        $this->mountLodgingRelations($manager);
    }

    public function mountObjects(ObjectManager $manager): void
    {
        $this->bedFactory->make(10)->persist($manager);
        $this->lodgingFactory->make(10)->persist($manager);
        $this->fileFactory->make(30)->persist($manager);
        $this->reservationFactory->make(10)->persist($manager);
        $this->reservationStatusFactory->make()->withCriteria(['name' => 'CONFIRMED'])->persist($manager);
        $this->reservationStatusFactory->make()->withCriteria(['name' => 'ARCHIVED'])->persist($manager);
        $this->reservationStatusFactory->make()->withCriteria(['name' => 'DELETED'])->persist($manager);
        $this->reservationStatusFactory->make()->withCriteria(['name' => 'PENDING'])->persist($manager);
        $manager->flush();
    }

    public function mountLodgingRelations(ObjectManager $manager): void
    {
        $beds = $this->bedRepository->findAll();
        $lodgings = $this->lodgingRepository->findAll();
        $files = $this->fileRepository->findAll();
        $reservations = $this->reservationRepository->findAll();
        $reservationStatuses = $this->reservationStatusRepository->findAll();

        foreach ($lodgings as $lodging) {

            for ($i = 0; $i < $lodging->getCapacity(); $i++) {
                shuffle($beds);
                $lodging->addBed($beds[0]);
            }

            for ($j = 0; $j < 10; $j++) {
                shuffle($files);
                $lodging->addFile($files[0]);
            }

            for ($k = 0; $k < 3; $k++) {
                shuffle($reservations);
                $lodging->addReservation($reservations[0]);
            }

            shuffle($reservationStatuses);
            $lodging->setReservationStatus($reservationStatuses[0]);

            $manager->persist($lodging);
        }

        $manager->flush();
    }


}
