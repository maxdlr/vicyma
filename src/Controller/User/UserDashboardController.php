<?php

namespace App\Controller\User;

use App\Crud\AddressCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Crud\MessageCrud;
use App\Crud\UserCrud;
use App\Entity\Address;
use App\Entity\Message;
use App\Enum\RoleEnum;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/user', name: 'app_user_account_')]
#[IsGranted(RoleEnum::ROLE_USER->value)]
class UserDashboardController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly AddressCrud               $addressCrud,
        private readonly UserCrud                  $userCrud,
        private readonly UserReservationController $userReservationController,
        private readonly UserController            $userController,
        private readonly UserConversationController $userConversationController,
        private readonly UserMessageController $userMessageController,
        private readonly MessageCrud $messageCrud,
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
        $user = $this->userController->getLoggedUser();

        $userData = $this->userController->getData();
        $reservationsData = $this->userReservationController->getData();

        $messageForm = $this->messageCrud->save($request, new Message(), ['user' => $user]);
        if ($messageForm === true) return $this->redirectTo('referer', $request);

        $userForm = $this->userCrud->save($request, $user);
        if ($userForm === true) return $this->redirectTo('referer', $request);

        $address = $user->getAddress() ?? new Address();
        $addressForm = $this->addressCrud->save($request, $address, ['user' => $user]);
        if ($addressForm === true) return $this->redirectTo('referer', $request);

        return $this->render('user/dashboard/dashboard.html.twig', [
            'user' => $userData,
            'addressForm' => $addressForm->createView(),
            'userForm' => $userForm->createView(),
            'reservations' => $reservationsData,
            'messageForm' => $messageForm->createView()
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    #[Route(path: '/conversations', name: 'conversations', methods: ['GET', 'POST'])]
    public function conversations(Request $request): Response
    {
        $user = $this->userController->getLoggedUser();

        $messageForm = $this->messageCrud->save($request, new Message(), ['user' => $user]);
        if ($messageForm === true) return $this->redirectTo('referer', $request);

        return $this->render('user/dashboard/inbox.html.twig', [
            'datatables' => [
                'conversations' => $this->userConversationController->getData(),
                'messages' => $this->userMessageController->getData(),
            ],
            'messageForm' => $messageForm->createView()
        ]);
    }
}