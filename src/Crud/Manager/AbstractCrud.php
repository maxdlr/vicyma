<?php

namespace App\Crud\Manager;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCrud
{
    protected string $entityClass;
    protected string $formTypeClass;

    public function __construct(
        protected DeleteManager $deleteManager,
        protected SaveManager   $saveManager,
    )
    {
    }

    public function create(Request $request): FormInterface|true
    {
        $object = new $this->entityClass();
        return $this->saveManager->handleAndSaveAll($object, $this->formTypeClass, $request);
    }

    public function edit(Request $request, object $object): FormInterface|true
    {
        return $this->saveManager->handleAndSaveAll($object, $this->formTypeClass, $request);
    }
}