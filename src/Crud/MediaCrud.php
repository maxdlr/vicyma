<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Media;
use App\Form\MediaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

#[CrudSetting(entity: Media::class, formType: MediaType::class)]
class MediaCrud extends AbstractCrud
{
    public function save(Request $request, object $object, array $options = [], ?callable $doBeforeSave = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object) {
            return $this->uploadManager->uploadOne($form, $object);
        });
    }
}