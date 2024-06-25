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
    public function save(
        Request $request,
        object $object,
        array $options = [],
        ?callable $doBeforeSave = null
    ): FormInterface|true
    {
        assert($object instanceof $this->entity);

        return $this->saveManager
            ->handleAndSave(
                $object,
                $this->formType,
                $request, $options,
                $doBeforeSave
            );
    }

    /**
     * Takes the $request and deletes the address object from the database.
     * By default, it just deletes the address object as is and redirects to the referer page.
     *
     * ```$doBeforeDelete()``` is an optional ?callable function that executes before the actual delete.
     * Return array|void
     * It inherits $object, $redirectRoute and $redirectParams.
     * @param Request $request
     * @param object $object
     * @param callable|null $doBeforeDelete
     * @return bool
     * @throws Exception
     * @example fn($object) => {}
     * If it returns void, it executes and delete() continues.
     * If it returns an array, the array can only contain 'save' or 'exit'.
     * If it returns 'save', it persists the address object, flushes and delete() continues.
     * If it returns 'exit', it interrupts delete() redirects to $redirectRoute.
     * If it returns both 'save' and 'exit', it will then persist the object, flush, interrupt delete() and redirect to $redirectRoute
     */
    public function delete(
        Request $request,
        object $object,
        ?callable $doBeforeDelete = null
    ): bool
    {
        assert($object instanceof $this->entity);
        return $this->deleteManager
            ->delete(
                $request,
                $object,
                $doBeforeDelete
            );
    }

    /**
     * @throws Exception
     */
    private function getCrudSetting(string $attributeArgument): string
    {
        $crudAttributes = ClassBrowser::findAttribute($this::class, CrudSetting::class);
        return $crudAttributes->getArguments()[$attributeArgument];
    }
}