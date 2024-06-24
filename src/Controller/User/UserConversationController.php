<?php

namespace App\Controller\User;

use App\Crud\Manager\AfterCrudTrait;
use App\Crud\MessageCrud;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Enum\RoleEnum;
use App\Repository\ConversationRepository;
use App\Service\UserManager;
use App\Vue\Model\VueDatatableSetting;
use App\Vue\VueFormatter;
use App\Vue\VueObjectMaker;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(attribute: RoleEnum::ROLE_USER->value)]
#[Route(path: '/user/conversation', name: 'app_user_account_conversation_')]
class UserConversationController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserManager            $userManager,
        private readonly MessageCrud            $messageCrud,
        private readonly UserMessageController  $userMessageController
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

        $messageForm = $this->messageCrud->save(request: $request, object: new Message(), options: ['user' => $user]);
        if ($messageForm === true) return $this->redirectTo(routeName: 'referer', request: $request);

        return $this->render(view: 'user/dashboard/inbox.html.twig', parameters: [
            'datatables' => [
                'conversations' => $this->getData(),
                'messages' => $this->userMessageController->getData(),
            ],
            'messageForm' => $messageForm->createView()
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function getData(): array
    {
        $userConversations = $this->conversationRepository->findBy(['user' => $this->userManager->user]);
        $creationDates = VueObjectMaker::makeVueObjectOf(entities: $userConversations, properties: ['createdOn'])->regroup(property: 'createdOn')->get();
        $isArchivedByUser = VueObjectMaker::makeVueObjectOf(entities: $userConversations, properties: ['isArchivedByUser'])->regroup(property: 'isArchivedByUser')->get();
        $conversations = VueObjectMaker::makeVueObjectOf(entities: $userConversations, properties: [
            'id', 'createdOn', 'messages', 'conversationId', 'isArchivedByUser'
        ])->get();

        return VueFormatter::createDatatableComponent(
            name: 'conversations',
            component: 'UserConversations',
            settings: [
                new VueDatatableSetting(name: 'updated on', values: $creationDates, default: '', codeName: 'createdOn'),
                new VueDatatableSetting(name: 'archived', values: $isArchivedByUser, default: false, codeName: 'isArchivedByUser'),
            ],
            items: $conversations
        )->getAsVueObject();
    }

    #[Route(path: '/{id}/archive', name: 'archive', methods: ['GET'])]
    public function archive(
        Conversation $conversation,
        Request      $request
    ): Response
    {
        $conversation->setIsArchivedByUser(isArchivedByUser: true);

        $this->entityManager->persist(object: $conversation);
        $this->entityManager->flush();

        return $this->redirectTo(routeName: 'referer', request: $request);
    }
}