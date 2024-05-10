<?php

namespace App\Controller\Admin;

use App\Crud\Manager\AfterCrudTrait;
use App\Crud\ReservationCrud;
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
    use AfterCrudTrait;

    public function __construct(
        private readonly ReservationStatusRepository $reservationStatusRepository,
        private readonly ReservationRepository       $reservationRepository,
        private readonly LodgingRepository           $lodgingRepository,
        private readonly EntityManagerInterface      $entityManager,
        private readonly ReservationCrud             $reservationCrud
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    #[Route(path: '/reservations', name: 'widget', methods: ['GET'])]
    public function widget(): Response
    {
        $reservations = $this->getReservationRequestData();

        return $this->render('', [
            'reservations' => $reservations['items'],
            'reservationFilters' => $reservations['settings'],
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        Reservation $reservation,
        Request     $request
    ): Response
    {
        $reservationForm = $this->reservationCrud->save($request, $reservation);

        if ($reservationForm === true) return $this->redirectTo('referer', $request);

        return $this->render('admin/show/reservation-details.html.twig', [
            'reservationForm' => $reservationForm,
            'reservation' => $reservation
        ]);
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
        return $this->redirectTo('referer', $request, 'reservations');
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
        return $this->redirectTo('referer', $request, 'reservations');
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
        return $this->redirectTo('referer', $request, 'reservations');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws ReflectionException
     */
    public function getReservationRequestData(): array
    {
        $allReservations = $this->reservationRepository->findAll();

        $statuses = VueDataFormatter::makeVueObjectOf(
            $this->reservationStatusRepository->findAll(), ['name']
        )->regroup('name')->get();

        $clients = VueDataFormatter::makeVueObjectOf(
            $allReservations, ['user']
        )->regroup('user')->get();

        $lodgings = VueDataFormatter::makeVueObjectOf(
            $this->lodgingRepository->findAll(), ['name']
        )->regroup('name')->get();

        $reservations = VueDataFormatter::makeVueObjectOf(
            $allReservations,
            [
                'id',
                'reservationNumber',
                'reservationStatus',
                'lodgings',
                'user',
                'arrivalDate',
                'departureDate',
                'price',
            ])->get();

        return [
            'settings' => [
                'reservationStatus' => ['name' => 'status', 'default' => 'PENDING', 'values' => $statuses, 'codeName' => 'reservationStatus'],
                'user' => ['name' => 'clients', 'default' => '', 'values' => $clients, 'codeName' => 'user'],
                'lodgings' => ['name' => 'lodgings', 'default' => '', 'values' => $lodgings, 'codeName' => 'lodgings']
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