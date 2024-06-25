<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Vue\VueObjectMaker;
use Exception;
use ReflectionException;
use Symfony\Bundle\SecurityBundle\Security;

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
     * @throws Exception
     */
    public function getData(): ?array
    {
        $user = $this->user;
        if ($user === null) throw new Exception('No logged user found, cannot get data');

        return VueObjectMaker::makeVueObjectOf(
            [$user],
            ['id', 'firstname', 'lastname', 'email', 'phoneNumber', 'address', 'roles', 'reservations', 'messages']
        )->get()[0];
    }
}