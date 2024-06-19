<?php

namespace App\Controller\User;

use App\Crud\Manager\AfterCrudTrait;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\VueDataFormatter;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly UserRepository         $userRepository,
    )
    {
    }

    public function getLoggedUser(): ?User
    {
        $connectedUser = $this->getUser() ?? null;
        return $this->userRepository->findOneBy(['email' => $connectedUser->getUserIdentifier()]);
    }

    /**
     * @throws ReflectionException
     */
    public function getData(): ?array
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