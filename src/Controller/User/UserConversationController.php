<?php

namespace App\Controller\User;

use App\Crud\Manager\AfterCrudTrait;
use App\Crud\MessageCrud;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Enum\RoleEnum;
use App\Repository\ConversationRepository;
use App\Service\UserManager;
use App\Service\Vue\VueDatatableSetting;
use App\Service\Vue\VueFormatter;
use App\Service\Vue\VueObjectMaker;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
        private readonly EntityManagerInterface $entityManager,
        private readonly UserManager $userManager,
        private readonly MessageCrud $messageCrud,
        private readonly UserMessageController $userMessageController
    )
    {
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    #[Route(path: '/', name: 'dashboard', methods: ['GET', 'POST'])]
    public function conversations(Request $request): Response
    {
        $user = $this->userManager->user;

        $messageForm = $this->messageCrud->save($request, new Message(), ['user' => $user]);
        if ($messageForm === true) return $this->redirectTo('referer', $request);

        return $this->render('user/dashboard/inbox.html.twig', [
            'datatables' => [
                'conversations' => $this->getData(),
                'messages' => $this->userMessageController->getData(),
            ],
            'messageForm' => $messageForm->createView()
        ]);
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
        $userConversations = $this->conversationRepository->findBy(['user' => $this->userManager->user]);
        $creationDates = VueObjectMaker::makeVueObjectOf($userConversations, ['createdOn'])->regroup('createdOn')->get();
        $isArchivedByUser = VueObjectMaker::makeVueObjectOf($userConversations, ['isArchivedByUser'])->regroup('isArchivedByUser')->get();
        $conversations = VueObjectMaker::makeVueObjectOf($userConversations, [
            'id', 'createdOn', 'messages', 'conversationId', 'isArchivedByUser'
        ])->get();

        return VueFormatter::createDatatableComponent(
            name: 'conversations',
            component: 'UserConversations',
            settings: [
                new VueDatatableSetting('updated on', '', $creationDates, 'createdOn'),
                new VueDatatableSetting('archived', false, $isArchivedByUser, 'isArchivedByUser'),
            ],
            items: $conversations
        );
    }
}