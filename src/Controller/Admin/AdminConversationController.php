<?php

namespace App\Controller\Admin;

use App\Crud\Manager\AfterCrudTrait;
use App\Entity\Conversation;
use App\Enum\RoleEnum;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Service\Vue\VueDatatableSetting;
use App\Service\Vue\VueFormatter;
use App\Service\Vue\VueObjectMaker;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/admin/conversation', name: 'app_admin_conversation_')]
#[IsGranted(RoleEnum::ROLE_ADMIN->value)]
class AdminConversationController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageRepository      $messageRepository,
    )
    {
    }

    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        Conversation $conversation
    ): Response
    {
        $latestMessage = $this->messageRepository->findBy(
            ['conversation' => $conversation, 'admin' => null],
            ['createdOn' => 'DESC'],
            1
        )[0];

        return $this->redirectTo(
            routeName: 'app_admin_message_reply',
            routeParams: ['id' => $latestMessage->getId()]
        );
    }

    #[Route(path: '/{id}/delete', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(
        Conversation $conversation
    ): Response
    {
        $this->entityManager->remove($conversation);
        $this->entityManager->flush();

        return $this->redirectTo('app_admin_conversations');
    }

    /**
     * @throws ReflectionException
     */
    public function getData(): array
    {
        $allConversations = $this->conversationRepository->findAll();
        $creationDates = VueObjectMaker::makeVueObjectOf($allConversations, ['createdOn'])->regroup('createdOn')->get();
        $conversations = VueObjectMaker::makeVueObjectOf(
            $allConversations, ['id', 'conversationId', 'user', 'createdOn']
        )->get();

        return VueFormatter::createDatatableComponent(
            name: 'conversations',
            component: 'AdminConversations',
            settings: [
                new VueDatatableSetting('creation date', '', $creationDates, 'createdOn')
            ],
            items: $conversations
        );
    }
}