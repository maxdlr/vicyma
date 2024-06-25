<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Crud\Manager\CrudSetting;
use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

#[CrudSetting(entity: Address::class, formType: AddressType::class)]
class AddressCrud extends AbstractCrud
{
    use AfterCrudTrait;
    public function save(Request $request, object $object, array $options = [], ?callable $doBeforeSave = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object) use ($options) {

            $user = $options['user'];
            assert($object instanceof Address);
            assert($user instanceof User);
            $user->setAddress($object);
            return true;

        });
    }
}
