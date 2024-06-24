<?php

namespace App\Controller\Admin;

use App\Crud\AdminMessageCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Crud\MessageCrud;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Enum\RoleEnum;
use App\Repository\MessageRepository;
use App\Service\Vue\VueDatatableSetting;
use App\Service\Vue\VueFormatter;
use App\Service\Vue\VueObjectMaker;
use App\ValueObject\ConversationId;
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
        if (assert(in_array(RoleEnum::ROLE_ADMIN->value, $admin->getRoles()))) {
            $message->setIsReadByAdmin(true);
            $this->entityManager->persist($message);
            $this->entityManager->flush();
        }

        return $this->render('admin/message/message-details.html.twig', [
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
            return $this->redirectTo('app_login', $request);
        }

        $responseMessage = new Message();
        $messageForm = $this->adminMessageCrud->save(
            $request,
            $responseMessage,
            ['admin' => $admin],
            function () use ($userMessage, $responseMessage) {
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

        if ($messageForm === true) return $this->redirectTo('referer', $request);

        return $this->render('admin/message/admin-conversation.html.twig', [
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
        return $this->messageCrud->delete($request, $message, 'app_admin_business', anchor: 'messages');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws ReflectionException
     */
    public function getData(): array
    {
        $allMessages = $this->messageRepository->findBy(['admin' => null]);
        $users = VueObjectMaker::makeVueObjectOf($allMessages, ['user'])->regroup('user')->get();
        $subjects = VueObjectMaker::makeVueObjectOf($allMessages, ['subject'])->regroup('subject')->get();
        $receptionDate = VueObjectMaker::makeVueObjectOf($allMessages, ['createdOn'])->regroup('createdOn')->get();
        $messages = VueObjectMaker::makeVueObjectOf($allMessages,
            [
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
                new VueDatatableSetting('clients', '', $users, 'user'),
                new VueDatatableSetting('subjects', '', $subjects, 'subject'),
                new VueDatatableSetting('reception date', '', $receptionDate, 'createdOn'),
            ],
            items: $messages
        );
    }
}