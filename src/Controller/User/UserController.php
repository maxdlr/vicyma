<?php

namespace App\Controller\User;

use App\Crud\Manager\AfterCrudTrait;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\VueDataFormatter;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly UserRepository $userRepository,
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
        $user = $this->getLoggedUser();

        if ($user === null) throw new AuthenticationCredentialsNotFoundException('No logged user found, cannot get data');

        return VueDataFormatter::makeVueObjectOf(
            [$user],
            $this->getEssentialUserPropertyKeys()
        )->get()[0];
    }

    private function getEssentialUserPropertyKeys(): array
    {
        return ['id', 'firstname', 'lastname', 'email', 'phoneNumber', 'address', 'roles', 'reservations', 'messages'];
    }
}