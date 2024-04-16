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
     * @param Request $request
     * @param object $object
     * @param array $options
     * @param callable|null $do
     * @return FormInterface|true
     * @throws Exception
     */
    public function save(Request $request, object $object, array $options = [], ?callable $do = null): FormInterface|true
    {
        return $this->saveManager->handleAndSave($object, $this->formType, $request, $options, $do);
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