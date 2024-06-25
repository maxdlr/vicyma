<?php

namespace App\Controller\User;

use App\Crud\Manager\AfterCrudTrait;
use App\Crud\MessageCrud;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Reservation;
use App\Enum\RoleEnum;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\UserManager;
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

#[IsGranted(attribute: RoleEnum::ROLE_USER->value)]
#[Route(path: '/user/message', name: 'app_user_account_message_')]
class UserMessageController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly MessageRepository      $messageRepository,
        private readonly MessageCrud            $messageCrud,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository         $userRepository,
        private readonly UserManager            $userManager
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

        foreach ($pastMessages as $pastMessage) {
            if ($pastMessage->getAdmin() !== null) {
                $pastMessage->setIsReadByUser(isReadByUser: true);
                $this->entityManager->persist(object: $pastMessage);
                $this->entityManager->flush();
            }
        }

        $newMessage = new Message();
        $newMessage
            ->setSubject(subject: $message->getSubject())
            ->setLodging(lodging: $message->getLodging())
            ->setReservation(reservation: $message->getReservation());

        $messageForm = $this->messageCrud->save(
            request: $request,
            object: $newMessage,
            options: [
                'user' => $this->userManager->user,
                'isReply' => true
            ],
            doBeforeSave: function () use ($message, $newMessage, $pastMessages) {

                if ($message->getConversation() === null) {
                    $conversation = new Conversation();
                    $conversation
                        ->setUser(user: $message->getUser())
                        ->addMessage(message: $message);
                } else {
                    $conversation = $message->getConversation();
                    $conversation->setUpdatedOn(updatedOn: new DateTime());
                }

                $newMessage->setSubject(subject: 'Message of ' . $conversation->getConversationId() . ' - ' . $message->getCreatedOn()->format(format: 'd/m/y'));

                $conversation
                    ->addMessage(message: $newMessage)
                    ->setConversationId(conversationId: ConversationId::new(message: $message));
            }
        );

        if ($messageForm === true) return $this->redirectTo(routeName: 'referer', request: $request);

        return $this->render(view: 'user/message/conversation.html.twig', parameters: [
            'messageForm' => $messageForm->createView(),
            'conversation' => $pastMessages,
            'userMessage' => $message
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/about-reservation/{id}/ask')]
    public function askAboutReservation(
        Request     $request,
        Reservation $reservation
    ): Response
    {
        $message = new Message();
        $message
            ->setSubject(subject: 'Question about ' . $reservation->getReservationNumber())
            ->setUser(user: $this->userManager->user)
            ->setReservation(reservation: $reservation);

        if (count(value: $reservation->getLodgings()) === 1) $message->setLodging(lodging: $reservation->getLodgings()[0]);

        $options = ['lodgings' => $reservation->getLodgings(), 'user' => $this->userManager->user];
        $messageForm = $this->messageCrud->save(request: $request, object: $message, options: $options);
        if ($messageForm === true) return $this->redirectTo(
            routeName: 'app_user_account_conversation_inbox',
            request: $request,
            anchor: 'messages'
        );

        return $this->render(view: 'user/message/new.html.twig', parameters: [
            "messageForm" => $messageForm->createView(),
            'reservation' => $reservation
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function getData(): array
    {
        $userMessages = $this->messageRepository->findBy([
            'user' => $this->userManager->user,
            'conversation' => null
        ]);
        $creationDates = VueObjectMaker::makeVueObjectOf(entities: $userMessages, properties: ['createdOn'])->regroup(property: 'createdOn')->get();
        $messages = VueObjectMaker::makeVueObjectOf(entities: $userMessages, properties: [
            'id', 'createdOn', 'subject', 'content', 'lodging', 'reservation', 'conversation', 'isReadByUser', 'admin', 'user'
        ])->get();

        return VueFormatter::createDatatableComponent(
            name: 'sent',
            component: 'UserMessages',
            settings: [
                new VueDatatableSetting(name: 'sent on', values: $creationDates, default: '', codeName: 'createdOn')
            ],
            items: $messages
        )->getAsVueObject();
    }
}