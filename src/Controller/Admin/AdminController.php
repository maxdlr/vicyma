<?php

namespace App\Controller\Admin;

use App\Enum\RoleEnum;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/admin', name: 'app_admin_')]
#[IsGranted(RoleEnum::ROLE_ADMIN->value)]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly AdminUserController         $adminUserController,
        private readonly AdminReservationController  $adminReservationController,
        private readonly AdminMessageController      $adminMessageController,
        private readonly AdminReviewController       $adminReviewController,
        private readonly AdminLodgingController      $adminLodgingController,
        private readonly AdminBedTypeController      $adminBedController,
        private readonly AdminConversationController $adminConversationController
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    #[Route(path: '/', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard/dashboard.html.twig', [
            'notifications' =>
                [
                    'reservations' => $this->adminReservationController->getNotification(),
                    'reviews' => $this->adminReviewController->getNotification()
                ]
        ]);
    }

    /**
     * @throws ReflectionException
     */
    #[Route(path: '/business', name: 'business', methods: ['GET', 'POST'])]
    public function business(): Response
    {
        return $this->render('admin/dashboard/business.html.twig', [
            'datatables' =>
                [
                    'reservations' => $this->adminReservationController->getData(),
                    'users' => $this->adminUserController->getData(),
                    'messages' => $this->adminMessageController->getData(),
                    'reviews' => $this->adminReviewController->getData()
                ]
        ]);
    }

    /**
     * @throws ReflectionException
     */
    #[Route(path: '/management', name: 'management', methods: ['GET', 'POST'])]
    public function management(): Response
    {
        return $this->render('admin/dashboard/management.html.twig', [
            'datatables' =>
                [
                    'lodgings' => $this->adminLodgingController->getData(),
                    'beds' => $this->adminBedController->getData(),
                    'users' => $this->adminUserController->getData(RoleEnum::ROLE_ADMIN),
                ]
        ]);
    }

    /**
     * @throws ReflectionException
     */
    #[Route(path: '/conversations', name: 'conversations', methods: ['GET', 'POST'])]
    public function conversations(): Response
    {
        return $this->render('admin/dashboard/conversations.html.twig', [
            'conversations' => $this->adminConversationController->getData()
        ]);
    }
}