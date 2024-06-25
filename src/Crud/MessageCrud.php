<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

#[CrudSetting(entity: Message::class, formType: MessageType::class)]
class MessageCrud extends AbstractCrud
{
    public function save(Request $request, object $object, array $options = [], ?callable $doBeforeSave = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object) use ($doBeforeSave, $options) {
            $user = $options['user'];

            assert($user instanceof User);
            assert($object instanceof Message);

            $object->setUser($user);

            if ($doBeforeSave !== null) $doBeforeSave();

            return true;
        }
        );
    }
}