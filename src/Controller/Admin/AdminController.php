<?php

namespace App\Controller\Admin;

use App\Form\LodgingType;
use App\Repository\LodgingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private LodgingRepository $lodgingRepository
    )
    {
    }

    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        $lodgings = $this->lodgingRepository->findAll();
        $lodgingForm = $this->createForm(LodgingType::class, $lodgings[0]);


        return $this->render('admin/dashboard.html.twig', [
            'lodgings' => $lodgings,
            'lodgingForm' => $lodgingForm,
        ]);
    }
}