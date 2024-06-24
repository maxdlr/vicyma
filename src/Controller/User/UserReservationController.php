<?php

namespace App\Controller\User;

use App\Enum\ReservationStatusEnum;
use App\Repository\ReservationRepository;
use App\Repository\ReservationStatusRepository;
use App\Service\UserManager;
use App\Service\Vue\VueDatatableSetting;
use App\Service\Vue\VueFormatter;
use App\Service\Vue\VueObjectMaker;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserReservationController extends AbstractController
{
    public function __construct(
        private readonly ReservationStatusRepository $reservationStatusRepository,
        private readonly ReservationRepository       $reservationRepository,
        private readonly UserManager                 $userManager
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    public function getData(): array
    {
        $user = $this->userManager->user;
        $userReservations = $this->reservationRepository->findBy(['user' => $user]);
        $creationDates = VueObjectMaker::makeVueObjectOf(entities: $userReservations, properties: ['createdOn'])->regroup('createdOn')->get();
        $statuses = VueObjectMaker::makeVueObjectOf(
            entities: [
                ...$this->reservationStatusRepository->findBy(['name' => ReservationStatusEnum::PENDING->value]),
                ...$this->reservationStatusRepository->findBy(['name' => ReservationStatusEnum::CONFIRMED->value]),
                ...$this->reservationStatusRepository->findBy(['name' => ReservationStatusEnum::ARCHIVED->value]),
            ],
            properties: ['name']
        )
            ->regroup('name')
            ->get();
        $reservations = VueObjectMaker::makeVueObjectOf(
            entities: $userReservations,
            properties: [
                'id',
                'reservationNumber',
                'arrivalDate',
                'departureDate',
                'adultCount',
                'childCount',
                'price',
                'reservationStatus',
                'lodgings',
                'createdOn',
                'updatedOn'
            ]
        )->get();

        return VueFormatter::createDatatable(
            settings: [
                new VueDatatableSetting(name: 'sent on', values: $creationDates, default: '', codeName: 'createdOn'),
                new VueDatatableSetting(name: 'status', values: $statuses, default: 'PENDING', codeName: 'reservationStatus')
            ],
            items: $reservations
        );
    }
}