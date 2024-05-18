<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Address::class, formType: AddressType::class)]
class AddressCrud extends AbstractCrud
{
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


    /**
     * Takes the $request and deletes the address object from the database.
     * By default, it just deletes the address object as is and redirects to the referer page.
     *
     * $redirectRoute is an optional string that has to be a valid route name.
     *
     * $redirectParams is an optional array that has to be valid parameters associated with $redirectRoute
     *
     * $doBeforeDelete() is an optional ?callable function that executes before the actual delete.
     * Return array|void
     * It inherits $object, $redirectRoute and $redirectParams.
     * @param callable|null $doBeforeDelete
     * @throws Exception
     *
     * @example fn($object, $redirectRoute, $redirectParams) => {}
     * If it returns void, it executes and delete() continues.
     * If it returns an array, the array can only contain 'save' or 'exit'.
     * If it returns 'save', it persists the address object, flushes and delete() continues.
     * If it returns 'exit', it interrupts delete() redirects to $redirectRoute.
     * If it returns both 'save' and 'exit', it will then persist the object, flush, interrupt delete() and redirect to $redirectRoute
     *
     */
    #[Route('address/{id}', name: 'app_address_delete', methods: ['POST'])]
    public function delete(Request $request, Address $object, string $redirectRoute = 'app_home', array $redirectParams = [], ?callable $doBeforeDelete = null): Response
    {
        return $this->deleteManager->delete($request, $object, $redirectRoute, $redirectParams, $doBeforeDelete);
    }
}