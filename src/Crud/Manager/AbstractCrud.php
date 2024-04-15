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
     * @param UploadManager $uploadManager
     * @throws Exception
     */
    public function __construct(
        protected SaveManager   $saveManager,
        protected DeleteManager $deleteManager,
        protected UploadManager $uploadManager,
    )
    {
        $this->entity = $this->getCrudSetting('entity');
        $this->formType = $this->getCrudSetting('formType');
    }

    /**
     * @throws Exception
     */
    public function save(Request $request, object $object): FormInterface|true
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