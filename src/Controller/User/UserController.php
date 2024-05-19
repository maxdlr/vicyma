<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\VueDataFormatter;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/user', name: 'app_user_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    #[Route(path: '/{id}/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(User $thisUser): Response
    {
        $user = $this->getUserDataById($thisUser->getId());

        return $this->render('user/dashboard/dashboard.html.twig', [
            'user' => $user
        ]);
    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * @throws ReflectionException
     */
    public function getConnectedUserData(): ?array
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
        return ['id', 'firstname', 'lastname', 'email', 'phoneNumber', 'address', 'roles'];
    }
}