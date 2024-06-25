<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Vue\VueObjectMaker;
use ReflectionException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

readonly class UserManager
{
    public ?User $user;

    public function __construct(
        private Security       $security,
        private UserRepository $userRepository
    )
    {
        $this->user = $this->getLoggedUser();
    }

    private function getLoggedUser(): ?User
    {
        return $this->userRepository
            ->findOneBy(
                [
                    'email' => $this->security->getUser()?->getUserIdentifier()
                ]
            );
    }

    /**
     * @throws ReflectionException
     */
    public function getData(): ?array
    {
        $user = $this->user;

        if ($user === null) throw new Exception('No logged user found, cannot get data');

        return VueObjectMaker::makeVueObjectOf([$user], self::getEssentialUserPropertyKeys())
            ->get()[0];
    }

    private static function getEssentialUserPropertyKeys(): array
    {
        return ['id', 'firstname', 'lastname', 'email', 'phoneNumber', 'address', 'roles', 'reservations', 'messages'];
    }
}