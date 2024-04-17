<?php

namespace App\Crud\Manager;

use App\Service\ClassBrowser;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Maxime de la Rocheterie
 */
abstract class AbstractCrud
{
    /**
     * Fully Qualified Class Name
     * @var string
     */
    protected string $entity;
    /**
     * Fully Qualified Class Name
     * @var string
     */
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
     * Creates and handles a Symfony FormType.
     * If the form is submitted, it returns true, otherwise it returns the form.
     *
     * @param Request $request
     * @param object $object
     * @param array $options
     * @param callable|null $doBeforeSave
     * @return FormInterface|true
     * @throws Exception
     */
    public function save(Request $request, object $object, array $options = [], ?callable $doBeforeSave = null): FormInterface|true
    {
        return $this->saveManager->handleAndSave($object, $this->formType, $request, $options, $doBeforeSave);
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