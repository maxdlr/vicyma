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
use App\Vue\Model\VueDatatableSetting;
use App\Vue\VueFormatter;
use App\Vue\VueObjectMaker;
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
        $reservationForm = $this->reservationCrud->save(request: $request, object: $reservation);
        if ($reservationForm === true)
            return $this->redirectTo(routeName: 'referer', request: $request)->do();

        return $this->render(view: 'admin/reservation/reservation-details.html.twig', parameters: [
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
        $reservationForm = $this->reservationCrud->save(request: $request, object: $reservation);

        if ($reservationForm === true) {

            $newReservationId = $this->reservationRepository
                ->findOneBy(
                    ['reservationNumber' => $reservation->getReservationNumber()],
                    ['createdOn' => 'DESC']
                )->getId();

            return $this->redirectTo('app_admin_reservation_show',null, ['id' => $newReservationId])->do();
        }

        return $this->render(view: 'admin/reservation/reservation-new.html.twig', parameters: [
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
        $this->editStatus(reservation: $reservation, reservationStatusEnum: ReservationStatusEnum::CONFIRMED);
        return $this->redirectTo(routeName: 'referer', request: $request)->do();
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
            $reservation->setReservationStatus(
                $this->reservationStatusRepository->findOneByName($reservationStatusEnum->value)
            );
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/delete', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(
        Reservation $reservation,
        Request $request
    ): Response
    {
        $this->reservationCrud->delete($request, $reservation, function ($object) {
            assert($object instanceof Reservation);
            $object->setReservationStatus(
                $this->reservationStatusRepository->findOneByName(ReservationStatusEnum::DELETED->value)
            );
            return ['save', 'exit'];
        });
        return $this->redirectTo(routeName: 'app_admin_business')->withAnchor('reservations')->do();
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
        $this->editStatus(reservation: $reservation, reservationStatusEnum: ReservationStatusEnum::ARCHIVED);
        return $this->redirectTo(routeName: 'referer', request: $request)->do();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/paid', name: 'paid', methods: ['GET'])]
    public function paid(
        Reservation $reservation,
        Request     $request
    ): Response
    {
        $this->editStatus(reservation: $reservation, reservationStatusEnum: ReservationStatusEnum::PAID);
        return $this->redirectTo(routeName: 'referer', request: $request)->do();
    }

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
            entities: $pendingReservations,
            properties: ['id', 'createdOn', 'user', 'lodgings', 'arrivalDate', 'departureDate']
        )->get();
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function getData(): array
    {
        $allReservations = $this->reservationRepository->findAll();
        $arrivalDates = VueObjectMaker::makeVueObjectOf(entities: $allReservations, properties: ['arrivalDate'])->regroup('arrivalDate')->get();
        $statuses = VueObjectMaker::makeVueObjectOf(entities: $this->reservationStatusRepository->findAll(), properties: ['name'])->regroup('name')->get();
        $clients = VueObjectMaker::makeVueObjectOf(entities: $allReservations, properties: ['user'])->regroup('user')->get();
        $lodgings = VueObjectMaker::makeVueObjectOf(entities: $this->lodgingRepository->findAll(), properties: ['name'])->regroup('name')->get();

        $reservations = VueObjectMaker::makeVueObjectOf(entities: $allReservations,
            properties: [
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
                new VueDatatableSetting(name: 'status', values: $statuses, default: 'PENDING', codeName: 'reservationStatus'),
                new VueDatatableSetting(name: 'clients', values: $clients, default: '', codeName: 'user'),
                new VueDatatableSetting(name: 'lodgings', values: $lodgings, default: '', codeName: 'lodgings'),
                new VueDatatableSetting(name: 'check in date', values: $arrivalDates, default: '', codeName: 'arrivalDate'),
            ],
            items: $reservations
        )->getAsVueObject();
    }
}