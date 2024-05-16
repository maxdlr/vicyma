<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Navigation extends AbstractController
{
    public function getAdminNavigation(): array
    {
        return [
            'home' =>
                [
                    'label' => 'site',
                    'value' => $this->generateUrl('app_home'),
                    'iconClass' => 'house-fill'
                ],
            'dashboard' =>
                [
                    'label' => 'dashboard',
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
}