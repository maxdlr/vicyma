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
    public function save(Request $request, object $object, array $options = [], ?callable $do = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object) {
            return $this->uploadManager->uploadMany($form, $object);
        });
    }

    #[Route('lodging/{id}', name: 'app_lodging_delete', methods: ['POST'])]
    public function delete(Request $request, Lodging $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'app_home');
    }
}