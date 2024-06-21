<?php

namespace App\Controller\User;

use App\Crud\Manager\AfterCrudTrait;
use App\Entity\Conversation;
use App\Enum\RoleEnum;
use App\Repository\ConversationRepository;
use App\Service\VueDataFormatter;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(RoleEnum::ROLE_USER->value)]
#[Route(path: '/user/conversation', name: 'app_user_account_conversation_')]
class UserConversationController extends AbstractController
{
    use AfterCrudTrait;
    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly UserController         $userController,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    #[Route(path: '/{id}/archive', name: 'archive', methods: ['GET'])]
    public function archive(
        Conversation $conversation,
        Request $request
    ): Response
    {
        $conversation->setIsArchivedByUser(true);

        $this->entityManager->persist($conversation);
        $this->entityManager->flush();

        return $this->redirectTo('referer', $request);
    }

    /**
     * @throws ReflectionException
     */
    public function getData(): array
    {
        $userConversations = $this->conversationRepository->findBy(['user' => $this->userController->getLoggedUser()]);
        $creationDates = VueDataFormatter::makeVueObjectOf($userConversations, ['createdOn'])->regroup('createdOn')->get();
        $isArchivedByUser = VueDataFormatter::makeVueObjectOf($userConversations, ['isArchivedByUser'])->regroup('isArchivedByUser')->get();
        $conversations = VueDataFormatter::makeVueObjectOf($userConversations, [
            'id', 'createdOn', 'messages', 'conversationId', 'isArchivedByUser'
        ])->get();

        return [
            'name' => 'conversations',
            'component' => 'UserConversations',
            'data' => [
                'settings' => [
                    'createdOn' => ['name' => 'updated on', 'default' => '', 'values' => $creationDates, 'codeName' => 'createdOn'],
                    'isArchivedByUser' => ['name' => 'archived', 'default' => false, 'values' => $isArchivedByUser, 'codeName' => 'isArchivedByUser']
                ],
                'items' => $conversations
            ]
        ];
    }
}