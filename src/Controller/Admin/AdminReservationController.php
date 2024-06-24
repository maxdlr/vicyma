<?php

namespace App\Controller\Admin;

use App\Crud\Manager\AfterCrudTrait;
use App\Crud\ReservationCrud;
use App\Entity\Reservation;
use App\Enum\ReservationStatusEnum;
use App\Enum\RoleEnum;
use App\Repository\LodgingRepository;
use App\Repository\ReservationRepository;
use App\Repository\ReservationStatusRepository;
use App\Service\Vue\VueDatatableSetting;
use App\Service\Vue\VueFormatter;
use App\Service\Vue\VueObjectMaker;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/admin/reservation', name: 'app_admin_reservation_')]
#[IsGranted(RoleEnum::ROLE_ADMIN->value)]
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
     * @throws Exception
     */
    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        Reservation $reservation,
        Request     $request
    ): Response
    {
        $reservationForm = $this->reservationCrud->save($request, $reservation);

        if ($reservationForm === true) return $this->redirectTo('referer', $request, 'reservations');

        return $this->render('admin/reservation/reservation-details.html.twig', [
            'reservationForm' => $reservationForm->createView(),
            'reservation' => $reservation
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $reservation = new Reservation();
        $reservationForm = $this->reservationCrud->save($request, $reservation);

        if ($reservationForm === true) return $this->redirectTo('app_admin_business', $request, 'reservations');

        return $this->render('admin/reservation/reservation-new.html.twig', [
            'reservationForm' => $reservationForm->createView(),
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

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/paid', name: 'paid', methods: ['GET'])]
    public function paid(
        Reservation $reservation,
        Request     $request
    ): Response
    {
        $this->editStatus($reservation, ReservationStatusEnum::PAID);
        return $this->redirectTo('referer', $request, 'reservations');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws ReflectionException
     */
    public function getNotification(): array
    {
        $pendingReservations = $this->reservationStatusRepository
            ->findOneBy(['name' => ReservationStatusEnum::PENDING->value])
            ->getReservations()
            ->toArray();

        return VueObjectMaker::makeVueObjectOf(
            $pendingReservations,
            ['id', 'createdOn', 'user', 'lodgings', 'arrivalDate', 'departureDate']
        )->get();
    }

    /**
     * @throws ReflectionException
     */
    public function getData(): array
    {
        $allReservations = $this->reservationRepository->findAll();
        $arrivalDates = VueObjectMaker::makeVueObjectOf($allReservations, ['arrivalDate'])->regroup('arrivalDate')->get();
        $statuses = VueObjectMaker::makeVueObjectOf($this->reservationStatusRepository->findAll(), ['name'])->regroup('name')->get();
        $clients = VueObjectMaker::makeVueObjectOf($allReservations, ['user'])->regroup('user')->get();
        $lodgings = VueObjectMaker::makeVueObjectOf($this->lodgingRepository->findAll(), ['name'])->regroup('name')->get();

        $reservations = VueObjectMaker::makeVueObjectOf($allReservations,
            [
                'id',
                'reservationNumber',
                'reservationStatus',
                'lodgings',
                'user',
                'arrivalDate',
                'departureDate',
                'price',
                'createdOn'
            ])->get();

        return VueFormatter::createDatatableComponent(
            name: 'reservations',
            component: 'AdminReservationRequests',
            settings: [
                new VueDatatableSetting('status', 'PENDING', $statuses, 'reservationStatus'),
                new VueDatatableSetting('clients', '', $clients, 'user'),
                new VueDatatableSetting('lodgings', '', $lodgings, 'lodgings'),
                new VueDatatableSetting('check in date', '', $arrivalDates, 'arrivalDate'),
            ],
            items: $reservations
        );
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