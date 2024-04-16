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
use App\ValueObject\ReservationNumber;
use DateInterval;
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


    #[Route('reservation/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'app_home');
    }
}