<?php

namespace App\Controller\User;

use App\Crud\Manager\AfterCrudTrait;
use App\Crud\MessageCrud;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Service\VueDataFormatter;
use App\ValueObject\ConversationId;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/user/message', name: 'app_user_account_message_')]
class UserMessageController extends AbstractController
{
    use AfterCrudTrait;
    public function __construct(
        private readonly MessageRepository $messageRepository,
        private readonly UserController $userController,
        private readonly MessageCrud $messageCrud,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function reply(
        Message $message,
        Request $request
    ): Response
    {
        $pastMessages = $message->getConversation()?->getMessages();
        if ($pastMessages === null) {
            $pastMessages = [$message];
        }

        $newMessage = new Message();
        $newMessage
            ->setSubject($message->getSubject())
            ->setLodging($message->getLodging())
            ->setReservation($message->getReservation());

        $messageForm = $this->messageCrud->save(
            $request,
            $newMessage,
            [
                'user' => $this->userController->getLoggedUser(),
                'isReply' => true
            ],
            function () use ($message, $newMessage) {

                if ($message->getConversation() === null) {
                    $conversation = new Conversation();
                    $conversation
                        ->setUser($message->getUser())
                        ->addMessage($message)
                    ;
                } else {
                    $conversation = $message->getConversation();
                    $conversation->setUpdatedOn(new DateTime());
                }

                $newMessage->setSubject('Message of ' . $conversation->getConversationId() . ' - ' . $message->getCreatedOn()->format('d/m/y'));
                $conversation
                    ->addMessage($newMessage)
                    ->setConversationId(ConversationId::new($message));
            }
        );

        if ($messageForm === true) return $this->redirectTo('referer', $request);

        return $this->render('user/message/conversation.html.twig', [
            'messageForm' => $messageForm->createView(),
            'conversation' => $pastMessages,
            'userMessage' => $message
        ]);
    }

    /**
     * @throws ReflectionException
     */
    public function getData(): array
    {
        $userMessages = $this->messageRepository->findBy([
            'user' => $this->userController->getLoggedUser(),
            'conversation' => null
        ]);
        $creationDates = VueDataFormatter::makeVueObjectOf($userMessages, ['createdOn'])->regroup('createdOn')->get();
        $messages = VueDataFormatter::makeVueObjectOf($userMessages, [
            'id', 'createdOn', 'subject', 'content', 'lodging', 'reservation', 'conversation'
        ])->get();

        return [
            'name' => 'sent',
            'component' => 'UserMessages',
            'data' => [
                'settings' => [
                    'createdOn' => ['name' => 'sent on', 'default' => '', 'values' => $creationDates, 'codeName' => 'createdOn']
                ],
                'items' => $messages
            ]
        ];
    }
}