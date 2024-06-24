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

#[IsGranted(RoleEnum::ROLE_USER->value)]
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
                $pastMessage->setIsReadByUser(true);
                $this->entityManager->persist($pastMessage);
                $this->entityManager->flush();
            }
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
                'user' => $this->userManager->user,
                'isReply' => true
            ],
            function () use ($message, $newMessage, $pastMessages) {

                if ($message->getConversation() === null) {
                    $conversation = new Conversation();
                    $conversation
                        ->setUser($message->getUser())
                        ->addMessage($message);
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
            ->setSubject('Question about ' . $reservation->getReservationNumber())
            ->setUser($this->userManager->user)
            ->setReservation($reservation);

        if (count($reservation->getLodgings()) === 1) $message->setLodging($reservation->getLodgings()[0]);

        $options = ['lodgings' => $reservation->getLodgings(), 'user' => $this->userManager->user];
        $messageForm = $this->messageCrud->save($request, $message, $options);
        if ($messageForm === true) return $this->redirectTo('referer', $request);

        return $this->render('user/message/new.html.twig', [
            "messageForm" => $messageForm->createView(),
            'reservation' => $reservation
        ]);
    }

    /**
     * @throws ReflectionException
     */
    public function getData(): array
    {
        $userMessages = $this->messageRepository->findBy([
            'user' => $this->userManager->user,
            'conversation' => null
        ]);
        $creationDates = VueObjectMaker::makeVueObjectOf($userMessages, ['createdOn'])->regroup('createdOn')->get();
        $messages = VueObjectMaker::makeVueObjectOf($userMessages, [
            'id', 'createdOn', 'subject', 'content', 'lodging', 'reservation', 'conversation', 'isReadByUser', 'admin', 'user'
        ])->get();

        return VueFormatter::createDatatableComponent(
            name: 'sent',
            component: 'UserMessages',
            settings: [
                new VueDatatableSetting('sent on', '', $creationDates, 'createdOn')
            ],
            items: $messages);
    }
}