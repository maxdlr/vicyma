<?php

namespace App\Crud\Manager;

use App\Service\ClassBrowser;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCrud
{
    protected string $entity;
    protected string $formType;

    /**
     * @param SaveManager $saveManager
     * @param DeleteManager $deleteManager
     * @throws Exception
     */
    public function __construct(
        protected SaveManager   $saveManager,
        protected DeleteManager $deleteManager,
    )
    {
        $this->entity = $this->getCrudSetting('entity');
        $this->formType = $this->getCrudSetting('formType');
    }

    public function create(Request $request): FormInterface|true
    {
        $object = new $this->entity();
        return $this->saveManager->handleAndSave($object, $this->formType, $request);
    }

    public function edit(Request $request, object $object): FormInterface|true
    {
        return $this->saveManager->handleAndSave($object, $this->formType, $request);
    }

    /**
     * @throws Exception
     */
    protected function getCrudSetting(string $attributeArgument): string
    {
        $crudAttributes = ClassBrowser::findAttribute($this::class, CrudSetting::class);
        return $crudAttributes->getArguments()[$attributeArgument];
    }
}