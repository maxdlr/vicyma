<?php

namespace App\Controller\User;

use App\Crud\AddressCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Entity\User;
use App\Enum\ReservationStatusEnum;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Service\VueDataFormatter;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/user', name: 'app_user_account_')]
class UserController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly UserRepository        $userRepository,
        private readonly AddressCrud           $addressCrud,
        private readonly ReservationRepository $reservationRepository
    )
    {
    }

    /**
     * @throws ReflectionException
     * @throws \Exception
     */
    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function dashboard(Request $request): Response
    {
        $user = $this->getLoggedUser();

        $address = $user?->getAddress();
        $addressForm = $this->addressCrud->save($request, $address, ['user' => $user]);
        if ($addressForm === true) return $this->redirectTo('referer', $request);

        $userData = $this->getUserDataById($user->getId());
        $reservationsData = $this->getReservationData();

        return $this->render('user/dashboard/dashboard.html.twig', [
            'addressForm' => $addressForm->createView(),
            'user' => $userData,
            'reservations' => $reservationsData,
        ]);
    }

    // -----------------------------------------------------------------------------------------------------------------

    public function getLoggedUser(): ?User
    {
        $connectedUser = $this->getUser();
        if ($connectedUser === null) return null;
        return $this->userRepository->findOneBy(['email' => $connectedUser->getUserIdentifier()]);
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
                'messages',
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

    /**
     * @throws ReflectionException
     */
    public function getUserDataById(int $id): ?array
    {
        return VueDataFormatter::makeVueObjectOf(
            [$this->userRepository->findOneBy(['id' => $id])],
            $this->getEssentialUserPropertyKeys()
        )->get()[0];
    }

    private function getEssentialUserPropertyKeys(): array
    {
        return ['id', 'firstname', 'lastname', 'email', 'phoneNumber', 'address', 'roles', 'reservations'];
    }
}