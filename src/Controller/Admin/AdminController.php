<?php

namespace App\Controller\Admin;

use App\Form\AddressType;
use App\Form\BedType;
use App\Form\MediaType;
use App\Form\LodgingType;
use App\Form\MessageType;
use App\Form\ReservationType;
use App\Form\ReviewType;
use App\Form\UserType;
use App\Repository\AddressRepository;
use App\Repository\BedRepository;
use App\Repository\MediaRepository;
use App\Repository\LodgingRepository;
use App\Repository\MessageRepository;
use App\Repository\ReservationRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        $userForm = $this->createForm(UserType::class);

        return $this->render('admin/dashboard.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }
}