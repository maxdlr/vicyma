<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Navigation extends AbstractController
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function getAdminNavigation(): array
    {
        return [
            'home' =>
                [
                    'label' => 'site',
                    'value' => $this->generateUrl('app_home'),
                    'iconClass' => 'house-fill'
                ],
            'dashboard.html.twig' =>
                [
                    'label' => 'dashboard.html.twig',
                    'value' => $this->generateUrl('app_admin_dashboard'),
                    'iconClass' => 'house-gear-fill'
                ],
            'Management' =>
                [
                    'label' => 'management',
                    'value' => $this->generateUrl('app_admin_management'),
                    'iconClass' => 'gear-fill'
                ],
            'business' =>
                [
                    'label' => 'business',
                    'value' => $this->generateUrl('app_admin_business'),
                    'iconClass' => 'briefcase-fill'
                ],
            'conversations' =>
                [
                    'label' => 'conversations',
                    'value' => $this->generateUrl('app_admin_conversations'),
                    'iconClass' => 'chat-dots-fill'
                ]
        ];
    }

    public function getUserNavigation(): array
    {
        return [];
    }

    public function getPublicNavigation(): array
    {
        $connectedUser = $this->getUser();
        $user = $connectedUser !== null ?
            $this->userRepository->findOneBy(['email' => $connectedUser->getUserIdentifier()]) :
            null;

        $userId = $user?->getId();
        $isUserAdmin = $user !== null && in_array('ROLE_ADMIN', $user?->getRoles());

        $nav = [
            'about' =>
                [
                    'label' => 'Who are we ?',
                    'value' => $this->generateUrl('app_about'),
                    'iconClass' => null
                ],
            'account' =>
                [
                    'label' => null,
                    'value' => $userId !== null ? $this->generateUrl('app_user_account_dashboard', ['id' => $userId]) : $this->generateUrl('app_login'),
                    'iconClass' => 'person-circle'
                ]
        ];

        if ($isUserAdmin) {
            $nav['admin'] = [
                'label' => 'admin',
                'value' => $this->generateUrl('app_admin_dashboard'),
                'iconClass' => 'person-circle'
            ];
            unset($nav['account']);
        }

        return $nav;
    }
}