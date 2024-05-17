<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Message;
use App\Entity\User;
use App\Enum\RoleEnum;
use App\Form\AdminMessageType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Message::class, formType: AdminMessageType::class)]
class AdminMessageCrud extends AbstractCrud
{
    public function save(Request $request, object $object, array $options = [], ?callable $doBeforeSave = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object) use ($doBeforeSave, $options) {
            $admin = $options['admin'];

            assert($admin instanceof User);
            assert(in_array(RoleEnum::ROLE_ADMIN->value, $admin->getRoles()));
            assert($object instanceof Message);

            $object->setAdmin($admin);

            $doBeforeSave();

            return true;
        }
        );
    }


    /**
     * Takes the $request and deletes the message object from the database.
     * By default, it just deletes the message object as is and redirects to the referer page.
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
     * @throws \Exception
     *
     * @example fn($object, $redirectRoute, $redirectParams) => {}
     * If it returns void, it executes and delete() continues.
     * If it returns an array, the array can only contain 'save' or 'exit'.
     * If it returns 'save', it persists the message object, flushes and delete() continues.
     * If it returns 'exit', it interrupts delete() redirects to $redirectRoute.
     *
     */
    #[Route('message/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $object, string $redirectRoute = 'referer', array $redirectParams = [], ?callable $doBeforeDelete = null): Response
    {
        return $this->deleteManager->delete($request, $object, $redirectRoute, $redirectParams, $doBeforeDelete);
    }
}