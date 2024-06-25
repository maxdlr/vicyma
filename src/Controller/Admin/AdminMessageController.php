<?php

namespace App\Controller\Admin;

use App\Crud\AdminMessageCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Crud\MessageCrud;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Enum\RoleEnum;
use App\Repository\MessageRepository;
use App\ValueObject\ConversationId;
use App\Vue\Model\VueDatatableSetting;
use App\Vue\VueFormatter;
use App\Vue\VueObjectMaker;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/admin/message', name: 'app_admin_message_')]
#[IsGranted(RoleEnum::ROLE_ADMIN->value)]
class AdminMessageController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly MessageRepository      $messageRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageCrud            $messageCrud,
        private readonly AdminMessageCrud       $adminMessageCrud
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        Message $message
    ): Response
    {
        $admin = $this->getUser();
        if (assert(in_array(needle: RoleEnum::ROLE_ADMIN->value, haystack: $admin->getRoles()))) {
            $message->setIsReadByAdmin(true);
            $this->entityManager->persist($message);
            $this->entityManager->flush();
        }

        return $this->render(view: 'admin/message/message-details.html.twig', parameters: [
            'message' => $message
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/reply', name: 'reply', methods: ['GET', 'POST'])]
    public function reply(
        Message $userMessage,
        Request $request
    ): Response
    {
        $pastMessages = $userMessage->getConversation()?->getMessages();
        if ($pastMessages === null) {
            $pastMessages = [$userMessage];
        }

        $admin = $this->getUser();
        if (!$admin) {
            return $this->redirectTo(routeName: 'app_login', request: $request);
        }

        $responseMessage = new Message();
        $messageForm = $this->adminMessageCrud->save(
            request: $request,
            object: $responseMessage,
            options: ['admin' => $admin],
            doBeforeSave: function () use ($userMessage, $responseMessage) {
                $userMessage->setIsReadByAdmin(true);

                if ($userMessage->getConversation() === null) {
                    $conversation = new Conversation();
                    $conversation->addMessage($userMessage);

                } else {
                    $conversation = $userMessage->getConversation();
                    $conversation->setUpdatedOn(new DateTime());
                }

                $responseMessage->setSubject('Response to ' . $userMessage->getUser()->getFullName() . ' - ' . $userMessage->getCreatedOn()->format('d/m/y'));
                $conversation
                    ->setUser($userMessage->getUser())
                    ->addMessage($responseMessage)
                    ->setConversationId(ConversationId::new($userMessage));
            }
        );

        if ($messageForm === true) return $this->redirectTo(routeName: 'referer', request: $request);

        return $this->render(view: 'admin/message/admin-conversation.html.twig', parameters: [
            'messageForm' => $messageForm->createView(),
            'conversation' => $pastMessages,
            'userMessage' => $userMessage
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/delete', name: 'delete', methods: ['GET'])]
    public function delete(
        Message $message,
        Request $request
    ): Response
    {
        return $this->messageCrud->delete(request: $request, object: $message, redirectRoute: 'app_admin_business', anchor: 'messages');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function getData(): array
    {
        $allMessages = $this->messageRepository->findBy(['admin' => null]);
        $users = VueObjectMaker::makeVueObjectOf(entities: $allMessages, properties: ['user'])->regroup('user')->get();
        $subjects = VueObjectMaker::makeVueObjectOf(entities: $allMessages, properties: ['subject'])->regroup('subject')->get();
        $receptionDate = VueObjectMaker::makeVueObjectOf(entities: $allMessages, properties: ['createdOn'])->regroup('createdOn')->get();
        $messages = VueObjectMaker::makeVueObjectOf(entities: $allMessages,
            properties: [
                'id',
                'user',
                'subject',
                'content',
                'lodging',
                'reservation',
                'createdOn'
            ])->get();

        return VueFormatter::createDatatableComponent(
            name: 'messages',
            component: 'AdminMessages',
            settings: [
                new VueDatatableSetting(name: 'clients', values: $users, default: '', codeName: 'user'),
                new VueDatatableSetting(name: 'subjects', values: $subjects, default: '', codeName: 'subject'),
                new VueDatatableSetting(name: 'reception date', values: $receptionDate, default: '', codeName: 'createdOn'),
            ],
            items: $messages
        )->getAsVueObject();
    }
}