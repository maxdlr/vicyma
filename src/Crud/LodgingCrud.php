<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Lodging;
use App\Form\LodgingType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

#[CrudSetting(entity: Lodging::class, formType: LodgingType::class)]
class LodgingCrud extends AbstractCrud
{
    public function save(Request $request, object $object, array $options = [], ?callable $doBeforeSave = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object) {
            return $this->uploadManager->uploadMany($form, $object);
        });
    }
}