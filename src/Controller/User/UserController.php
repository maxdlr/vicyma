<?php

namespace App\Controller\User;

use App\Crud\AddressCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Crud\UserCrud;
use App\Entity\Address;
use App\Entity\User;
use App\Enum\RoleEnum;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Service\VueDataFormatter;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/user', name: 'app_user_account_')]
#[IsGranted(RoleEnum::ROLE_USER->value)]
class UserController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly AddressCrud            $addressCrud,
        private readonly ReservationRepository  $reservationRepository,
        private readonly UserCrud               $userCrud,
        private readonly ConversationRepository $conversationRepository,
        private readonly MessageRepository      $messageRepository
    )
    {
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(Request $request): Response
    {
        $user = $this->getLoggedUser();

        $userData = $this->getLoggedUserData();
        $reservationsData = $this->getReservationData();

        $userForm = $this->userCrud->save($request, $user);
        if ($userForm === true) return $this->redirectTo('referer', $request);

        $addressForm = $this->handleAddress($user, $request);
        if ($addressForm === true) return $this->redirectTo('referer', $request);

        return $this->render('user/dashboard/dashboard.html.twig', [
            'user' => $userData,
            'addressForm' => $addressForm->createView(),
            'userForm' => $userForm->createView(),
            'reservations' => $reservationsData,
        ]);
    }

    /**
     * @throws ReflectionException
     */
    #[Route(path: '/conversations', name: 'conversations', methods: ['GET', 'POST'])]
    public function conversations(): Response
    {
        return $this->render('user/conversations.html.twig', [
            'datatables' => [
                'conversations' => $this->getConversationData(),
                'messages' => $this->getMessageData(),
            ]
        ]);
    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @throws Exception
     */
    public function handleAddress(User $user, Request $request): true|FormInterface
    {
        $address = $user->getAddress() ?? new Address();
        return $this->addressCrud->save($request, $address, ['user' => $user]);
    }

    // -----------------------------------------------------------------------------------------------------------------

    public function getLoggedUser(): ?User
    {
        $connectedUser = $this->getUser() ?? null;
        return $this->userRepository->findOneBy(['email' => $connectedUser->getUserIdentifier()]);
    }

    /**
     * @throws ReflectionException
     */
    public function getConversationData(): array
    {
        $userConversations = $this->conversationRepository->findBy(['user' => $this->getLoggedUser()]);
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

    /**
     * @throws ReflectionException
     */
    public function getMessageData(): array
    {
        $userMessages = $this->messageRepository->findBy(['user' => $this->getLoggedUser()]);
        $creationDates = VueDataFormatter::makeVueObjectOf($userMessages, ['createdOn'])->regroup('createdOn')->get();
        $messages = VueDataFormatter::makeVueObjectOf($userMessages, [
            'id', 'createdOn', 'subject', 'content', 'lodging', 'reservation', 'conversation'
        ])->get();

        return [
            'name' => 'messages',
            'component' => 'UserMessages',
            'data' => [
                'settings' => [
                    'createdOn' => ['name' => 'sent on', 'default' => '', 'values' => $creationDates, 'codeName' => 'createdOn']
                ],
                'items' => $messages
            ]
        ];
    }

    /**
     * @throws ReflectionException
     */
    public function getReservationData(): array
    {
        $user = $this->getLoggedUser();
        return VueDataFormatter::makeVueObjectOf(
            $this->reservationRepository->findBy(['user' => $user]),
            [
                'id',
                'reservationNumber',
                'arrivalDate',
                'departureDate',
                'adultCount',
                'childCount',
                'price',
                'reservationStatus',
                'lodgings',
                'createdOn',
                'updatedOn'
            ]
        )->get();
    }

    /**
     * @throws ReflectionException
     */
    public function getLoggedUserData(): ?array
    {
        $connectedUser = $this->getUser();

        return $connectedUser !== null ?
            VueDataFormatter::makeVueObjectOf(
                [$this->userRepository->findOneBy(['email' => $connectedUser->getUserIdentifier()])],
                $this->getEssentialUserPropertyKeys()
            )->get()[0] : null;
    }

    private function getEssentialUserPropertyKeys(): array
    {
        return ['id', 'firstname', 'lastname', 'email', 'phoneNumber', 'address', 'roles', 'reservations', 'messages'];
    }
}