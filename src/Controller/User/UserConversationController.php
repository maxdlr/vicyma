<?php

namespace App\Controller\User;

use App\Repository\ConversationRepository;
use App\Service\VueDataFormatter;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserConversationController extends AbstractController
{
    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly UserController         $userController,
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    public function getData(): array
    {
        $userConversations = $this->conversationRepository->findBy(['user' => $this->userController->getLoggedUser()]);
        $creationDates = VueDataFormatter::makeVueObjectOf($userConversations, ['createdOn'])->regroup('createdOn')->get();
        $conversations = VueDataFormatter::makeVueObjectOf($userConversations, [
            'id', 'createdOn', 'messages', 'conversationId'
        ])->get();

        return [
            'name' => 'conversations',
            'component' => 'UserConversations',
            'data' => [
                'settings' => [
                    'createdOn' => ['name' => 'updated on', 'default' => '', 'values' => $creationDates, 'codeName' => 'createdOn']
                ],
                'items' => $conversations
            ]
        ];
    }
}