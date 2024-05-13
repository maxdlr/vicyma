<?php

namespace App\Controller\Admin;

use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly AdminUserController        $adminUserController,
        private readonly AdminReservationController $adminReservationController,
        private readonly AdminMessageController     $adminMessageController,
        private readonly AdminReviewController      $adminReviewController
    )
    {
    }

    #[Route(path: '/', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard/dashboard.html.twig');
    }

    /**
     * @throws ReflectionException
     */
    #[Route(path: '/business', name: 'business', methods: ['GET', 'POST'])]
    public function business(): Response
    {
        $reservations = $this->adminReservationController->getData();
        $messages = $this->adminMessageController->getData();
        $reviews = $this->adminReviewController->getData();
        $users = $this->adminUserController->getData();

        return $this->render('admin/dashboard/business.html.twig', [
            'datatables' =>
                [
                    'reservations' => $reservations,
                    'users' => $users,
                    'messages' => $messages,
                    'reviews' => $reviews
                ]
        ]);
    }
}