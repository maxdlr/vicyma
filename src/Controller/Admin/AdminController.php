<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly UserRepository      $reservationRepository,
        private readonly AdminUserController $adminUserController,
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(): Response
    {
        $users = $this->adminUserController->getUserData();

        return $this->render('admin/dashboard/dashboard.html.twig', [
            'users' => $users['items'],
            'userFilters' => $users['filters'],
        ]);
    }
}