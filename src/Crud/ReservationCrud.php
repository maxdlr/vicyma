<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Crud\Manager\DeleteManager;
use App\Crud\Manager\SaveManager;
use App\Crud\Manager\UploadManager;
use App\Entity\Reservation;
use App\Enum\ReservationStatusEnum;
use App\Form\ReservationType;
use App\Repository\ReservationStatusRepository;
use App\Service\YieldManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

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
        ) {
            assert($object instanceof Reservation);

            if ($object->getArrivalDate()->diff($object->getDepartureDate())->invert) {
                return false;
            }

            $reservationPrice = 0;
            foreach ($object->getLodgings() as $lodging) {
                $reservationPrice += YieldManager::calculateReservationPrice(
                    $object->getArrivalDate(),
                    $object->getDepartureDate(),
                    $lodging
                );
            }

            $object
                ->setReservationNumber($object->getUser(), $object)
                ->setPrice($reservationPrice);

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
}