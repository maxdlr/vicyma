<?php

namespace App\Controller\Admin;

use App\Crud\ReservationCrud;
use App\Entity\Reservation;
use App\Enum\ReservationStatusEnum;
use App\Repository\ReservationStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/reservation', name: 'app_reservation_')]
class AdminReservationController extends AbstractController
{
    public function __construct(
        private readonly ReservationStatusRepository $reservationStatusRepository,
        private readonly EntityManagerInterface      $entityManager
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/confirm', name: 'confirm', methods: ['POST', 'GET'])]
    public function confirm(
        Reservation $reservation,
        Request     $request
    ): Response
    {
        try {
            $reservation->setReservationStatus(
                $this->reservationStatusRepository->findOneByName(ReservationStatusEnum::CONFIRMED->value)
            );
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $this->redirect($request->headers->get('referer'));
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}