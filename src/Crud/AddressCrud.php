<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudInterface;
use App\Crud\Manager\DeleteManager;
use App\Crud\Manager\SaveManager;
use App\Entity\Address;
use App\Form\AddressType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddressCrud extends AbstractCrud implements CrudInterface
{
    public function __construct(
        protected DeleteManager $deleteManager,
        protected SaveManager   $saveManager
    )
    {
        parent::__construct($this->deleteManager, $this->saveManager);
        $this->entityClass = Address::class;
        $this->formTypeClass = AddressType::class;
    }

    #[Route('/{id}', name: 'app_address_delete', methods: ['POST'])]
    public function delete(Request $request, object $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'app_home');
    }
}