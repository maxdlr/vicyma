<?php

namespace App\Controller\Admin;

use App\Crud\LodgingCrud;
use App\Repository\LodgingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(private readonly LodgingCrud $lodgingCrud, private readonly LodgingRepository $lodgingRepository)
    {
    }

    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(Request $request): Response
    {
        $lodging = $this->lodgingRepository->find(1);
        $lodgingForm = $this->lodgingCrud->edit($request, $lodging);

        if ($lodgingForm === true) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/dashboard.html.twig', [
            'lodgingForm' => $lodgingForm->createView(),
        ]);
    }
}