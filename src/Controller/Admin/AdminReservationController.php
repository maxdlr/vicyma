<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Enum\ReservationStatusEnum;
use App\Repository\LodgingRepository;
use App\Repository\ReservationRepository;
use App\Repository\ReservationStatusRepository;
use App\Service\VueDataFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/reservation', name: 'app_admin_reservation_')]
class AdminReservationController extends AbstractController
{
    public function __construct(
        private readonly ReservationStatusRepository $reservationStatusRepository,
        private readonly ReservationRepository       $reservationRepository,
        private readonly LodgingRepository           $lodgingRepository,
        private readonly EntityManagerInterface      $entityManager
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/confirm', name: 'confirm', methods: ['GET'])]
    public function confirm(
        Reservation $reservation,
        Request     $request
    ): Response
    {
        $this->editStatus($reservation, ReservationStatusEnum::CONFIRMED);
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/delete', name: 'delete', methods: ['GET'])]
    public function delete(
        Reservation $reservation,
        Request     $request
    ): Response
    {
        $this->editStatus($reservation, ReservationStatusEnum::DELETED);
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/archive', name: 'archive', methods: ['GET'])]
    public function archive(
        Reservation $reservation,
        Request     $request
    ): Response
    {
        $this->editStatus($reservation, ReservationStatusEnum::ARCHIVED);
        return $this->redirect($request->headers->get('referer'));
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws ReflectionException
     */
    public function getReservationRequestData(): array
    {
        $statuses = VueDataFormatter::makeVueObjectOf(
            $this->reservationStatusRepository->findAll(), ['name']
        )->regroup('name')->get();

        $clients = VueDataFormatter::makeVueObjectOf(
            $this->reservationRepository->findAll(), ['user']
        )->regroup('user')->get();

        $lodgings = VueDataFormatter::makeVueObjectOf(
            $this->lodgingRepository->findAll(), ['name']
        )->regroup('name')->get();

        $reservations = VueDataFormatter::makeVueObjectOf(
            $this->reservationRepository->findAll(),
            [
                'id',
                'reservationStatus',
                'lodgings',
                'user',
                'arrivalDate',
                'departureDate',
                'price',
            ])->get();

        return [
            'filters' => [
                'reservationStatus' => ['name' => 'Status', 'default' => 'PENDING', 'values' => $statuses],
                'user' => ['name' => 'clients', 'default' => '', 'values' => $clients],
                'lodgings' => ['name' => 'lodgings', 'default' => '', 'values' => $lodgings]
            ],
            'items' => $reservations
        ];
    }

    /**
     * @throws Exception
     */
    private function editStatus(
        Reservation           $reservation,
        ReservationStatusEnum $reservationStatusEnum,
    ): void
    {
        try {
            $reservation->setReservationStatus($this->reservationStatusRepository->findOneByName($reservationStatusEnum->value));
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}