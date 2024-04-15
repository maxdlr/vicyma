<?php

namespace App\Controller\Admin;

use App\Crud\LodgingCrud;
use App\Entity\Lodging;
use App\Repository\LodgingRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly LodgingCrud       $lodgingCrud,
        private readonly LodgingRepository $lodgingRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(Request $request): Response
    {
//        $lodging = $this->lodgingRepository->find(2);
        $lodging = new Lodging();
        $lodgingForm = $this->lodgingCrud->save($request, $lodging);

        if ($lodgingForm === true) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/dashboard.html.twig', [
            'lodgingForm' => $lodgingForm,
        ]);
    }
}