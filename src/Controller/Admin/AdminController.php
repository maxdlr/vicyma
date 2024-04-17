<?php

namespace App\Controller\Admin;

use App\Repository\ReservationRepository;
use App\Repository\ReservationStatusRepository;
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

    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(): Response
    {
        $reservations = $this->reservationRepository->findAll();
        $statuses = $this->reservationStatusRepository->findAll();

        return $this->render('admin/dashboard/dashboard.html.twig', [
            'reservations' => $reservations,
            'statuses' => $statuses
        ]);
    }
}