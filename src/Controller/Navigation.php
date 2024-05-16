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
                    'name' => 'site',
                    'value' => $this->generateUrl('app_home'),
                    'iconClass' => 'house-fill'
                ],
            'dashboard' =>
                [
                    'name' => 'dashboard',
                    'value' => $this->generateUrl('app_admin_dashboard'),
                    'iconClass' => 'house-gear-fill'
                ],
            'Management' =>
                [
                    'name' => 'management',
                    'value' => $this->generateUrl('app_admin_management'),
                    'iconClass' => 'gear-fill'
                ],
            'business' =>
                [
                    'name' => 'business',
                    'value' => $this->generateUrl('app_admin_business'),
                    'iconClass' => 'briefcase-fill'
                ],
            'conversations' =>
                [
                    'name' => 'conversations',
                    'value' => $this->generateUrl('app_admin_conversations'),
                    'iconClass' => 'chat-dots-fill'
                ]
        ];
    }
}