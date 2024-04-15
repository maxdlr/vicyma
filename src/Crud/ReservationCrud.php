<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Reservation;
use App\Entity\ReservationStatus;
use App\Enum\ReservationStatusEnum;
use App\Form\ReservationType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Reservation::class, formType: ReservationType::class)]
class ReservationCrud extends AbstractCrud
{
    public function save(Request $request, object $object, array $options = []): FormInterface|true
    {
        return $this->saveManager->handleAndSave(
            $object,
            $this->formType,
            $request,
            $options,
            function ($form, $object
            ) use ($options) {
                $object
                    ->addLodging($options['lodging'])
                    ->setPrice()
                    ->setReservationStatus(ReservationStatusEnum::PENDING->value)
                    ->setUser($options['user']);
            });
    }


    #[Route('reservation/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'app_home');
    }
}