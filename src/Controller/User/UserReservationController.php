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
        private readonly ReservationRepository $reservationRepository,
        private readonly UserManager $userManager
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
        $creationDates = VueObjectMaker::makeVueObjectOf($userReservations, ['createdOn'])->regroup('createdOn')->get();
        $statuses = VueObjectMaker::makeVueObjectOf(
            [
                ...$this->reservationStatusRepository->findBy(['name' => ReservationStatusEnum::PENDING->value]),
                ...$this->reservationStatusRepository->findBy(['name' => ReservationStatusEnum::CONFIRMED->value]),
                ...$this->reservationStatusRepository->findBy(['name' => ReservationStatusEnum::ARCHIVED->value]),
            ],
            ['name']
        )
            ->regroup('name')
            ->get();
        $reservations = VueObjectMaker::makeVueObjectOf(
            $userReservations,
            [
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
                new VueDatatableSetting('sent on', '', $creationDates, 'createdOn'),
                new VueDatatableSetting('status', 'PENDING', $statuses, 'reservationStatus')
            ],
            items: $reservations
        );
    }
}