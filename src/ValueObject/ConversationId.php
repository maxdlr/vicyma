<?php

namespace App\ValueObject;

use App\Entity\Message;

class ConversationId
{
    public static function new(Message $message): string
    {
        $userName = $message->getUser()->getFullName(true);
        $date = $message->getCreatedOn();
        $userId = $message->getId();

        return $userName . '-' . $date->format('dmY') . '-' . $userId;
    }
}