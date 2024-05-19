<?php

namespace App\Controller;

use App\Controller\User\UserController;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly UserController $userController,
        private readonly UserRepository $userRepository
    )
    {
    }

    /**
     * @throws \ReflectionException
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->userController->getConnectedUserData();

        return $this->render('home/index.html.twig', [
            'user' => $user
        ]);
    }
}