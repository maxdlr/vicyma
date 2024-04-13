<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Address;
use App\Entity\EntityInterface;
use App\Form\AddressType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Address::class, formType: AddressType::class)]
class AddressCrud extends AbstractCrud
{
    #[Route('/{id}', name: 'app_address_delete', methods: ['POST'])]
    public function delete(Request $request, Address $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'app_home');
    }
}