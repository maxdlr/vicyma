<?php

namespace App\Controller\User;

use App\Crud\ReservationCrud;
use App\Enum\ReservationStatusEnum;
use App\Repository\ReservationRepository;
use App\Repository\ReservationStatusRepository;
use App\Service\VueDataFormatter;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserReservationController extends AbstractController
{
    public function __construct(
        private readonly ReservationStatusRepository $reservationStatusRepository,
        private readonly ReservationRepository $reservationRepository,
        private readonly UserController $userController
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    public function getData(): array
    {
        $user = $this->userController->getLoggedUser();
        $userReservations = $this->reservationRepository->findBy(['user' => $user]);
        $creationDates = VueDataFormatter::makeVueObjectOf($userReservations, ['createdOn'])->regroup('createdOn')->get();
        $statuses = VueDataFormatter::makeVueObjectOf(
            [
                ...$this->reservationStatusRepository->findBy(['name' => ReservationStatusEnum::PENDING->value]),
                ...$this->reservationStatusRepository->findBy(['name' => ReservationStatusEnum::CONFIRMED->value]),
                ...$this->reservationStatusRepository->findBy(['name' => ReservationStatusEnum::ARCHIVED->value]),
            ],
            ['name']
        )
            ->regroup('name')
            ->get();
        $reservations = VueDataFormatter::makeVueObjectOf(
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

        return [
            'settings' => [
                'createdOn' => ['name' => 'sent on', 'default' => '', 'values' => $creationDates, 'codeName' => 'createdOn'],
                'reservationStatus' => [
                    'name' => 'status',
                    'default' => 'PENDING',
                    'values' => $statuses,
                    'codeName' => 'reservationStatus'
                ],
            ],
            'items' => $reservations
        ];

    }
}