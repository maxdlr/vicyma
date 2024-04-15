<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Lodging;
use App\Form\LodgingType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Lodging::class, formType: LodgingType::class)]
class LodgingCrud extends AbstractCrud
{
    public function save(Request $request, object $object): FormInterface|true
    {
        return $this->saveManager->handleAndSave(
            $object,
            $this->formType,
            $request,
            fn($form, $object) => $this->uploadManager->uploadMany($form)
        );
    }

    #[Route('lodging/{id}', name: 'app_lodging_delete', methods: ['POST'])]
    public function delete(Request $request, Lodging $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'app_home');
    }
}