<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use DateTime;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Message::class, formType: MessageType::class)]
class MessageCrud extends AbstractCrud
{
    public function save(Request $request, object $object, array $options = [], ?callable $do = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object) use ($options) {
            $user = $options['user'];

            assert($user instanceof User);
            assert($object instanceof Message);

            $object
                ->setSentOn(new DateTime('now'))
                ->setUser($options['user']);

            return true;
        }
        );
    }


    #[Route('message/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'app_home');
    }
}