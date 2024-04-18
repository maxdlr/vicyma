<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Crud\Manager\DeleteManager;
use App\Crud\Manager\SaveManager;
use App\Crud\Manager\UploadManager;
use App\Entity\Lodging;
use App\Entity\Reservation;
use App\Entity\ReservationStatus;
use App\Entity\User;
use App\Enum\ReservationStatusEnum;
use App\Form\ReservationType;
use App\Repository\ReservationStatusRepository;
use App\Service\YieldManager;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Reservation::class, formType: ReservationType::class)]
class ReservationCrud extends AbstractCrud
{
    public function __construct(
        SaveManager                                  $saveManager,
        DeleteManager                                $deleteManager,
        UploadManager                                $uploadManager,
        private readonly ReservationStatusRepository $reservationStatusRepository,
    )
    {
        parent::__construct($saveManager, $deleteManager, $uploadManager);
    }

    public function save(Request $request, object $object, array $options = [], ?callable $doBeforeSave = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object
        ) use ($options) {

            $lodging = $options['lodging'];
            $user = $options['user'];

            assert($lodging instanceof Lodging);
            assert($user instanceof User);
            assert($object instanceof Reservation);

            if ($object->getArrivalDate()->diff($object->getDepartureDate())->invert) {
                return false;
            }

            $reservationPrice = YieldManager::calculateReservationPrice(
                $object->getArrivalDate(),
                $object->getDepartureDate(),
                $lodging
            );

            $object
                ->setReservationNumber($user, $object)
                ->addLodging($lodging)
                ->setPrice($reservationPrice)
                ->setUser($user);

            if ($object->getId() === null) {
                $object->setReservationStatus(
                    $this->reservationStatusRepository->findOneBy(
                        [
                            'name' => ReservationStatusEnum::PENDING->value
                        ]
                    )
                );
            }
            return true;
        });
    }

    /**
     * Takes the $request and deletes the reservation object from the database.
     * By default, it just deletes the reservation object as is and redirects to the referer page.
     *
     * $redirectRoute is an optional string that has to be a valid route name.
     *
     * $redirectParams is an optional array that has to be valid parameters associated with $redirectRoute
     *
     * $doBeforeDelete() is an optional ?callable function that executes before the actual delete.
     * Return array|void
     * It inherits $object, $redirectRoute and $redirectParams.
     * @param callable|null $doBeforeDelete
     * @throws Exception
     *
     * @example fn($object, $redirectRoute, $redirectParams) => {}
     * If it returns void, it executes and delete() continues.
     * If it returns an array, the array can only contain 'save' or 'exit'.
     * If it returns 'save', it persists the reservation object, flushes and delete() continues.
     * If it returns 'exit', it interrupts delete() redirects to $redirectRoute.
     *
     */
    #[Route('reservation/{id}/delete', name: 'app_reservation_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Reservation $object, string $redirectRoute = 'referer', array $redirectParams = [], ?callable $doBeforeDelete = null): Response
    {
        return $this->deleteManager->delete($request, $object, $redirectRoute, $redirectParams, function ($object) {
            assert($object instanceof Reservation);
            $object->setReservationStatus($this->reservationStatusRepository->findOneByName(ReservationStatusEnum::DELETED->value));
            return ['save', 'exit'];
        });
    }
}