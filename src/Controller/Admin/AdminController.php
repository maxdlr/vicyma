<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Entity\ReservationStatus;
use App\Repository\ReservationRepository;
use App\Repository\ReservationStatusRepository;
use App\Service\VueDataFormatter;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly ReservationRepository       $reservationRepository,
        private readonly ReservationStatusRepository $reservationStatusRepository
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(): Response
    {
        $statuses = array_map(function (ReservationStatus $reservationStatus) {
            return VueDataFormatter::makeVueObject($reservationStatus, ['name']);
        }, $this->reservationStatusRepository->findAll());

        $reservations = array_map(function (Reservation $reservation) {
            return VueDataFormatter::makeVueObject(
                $reservation, [
                    'id',
                    'reservationStatus',
                    'lodgings',
                    'user',
                    'arrivalDate',
                    'departureDate',
                    'price',
                ]
            );
        }, $this->reservationRepository->findAll());

//        dump($reservations[0]);

        return $this->render('admin/dashboard/dashboard.html.twig', [
            'reservations' => $reservations,
            'statuses' => $statuses,
        ]);
    }
}