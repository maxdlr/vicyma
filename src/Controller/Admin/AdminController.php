<?php

namespace App\Controller\Admin;

use App\Crud\MediaCrud;
use App\Repository\MediaRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly MediaCrud       $mediaCrud,
        private readonly MediaRepository $mediaRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(Request $request): Response
    {
        $media = $this->mediaRepository->find(1);
        $mediaForm = $this->mediaCrud->edit($request, $media);

        if ($mediaForm === true) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/dashboard.html.twig', [
            'mediaForm' => $mediaForm->createView(),
        ]);
    }
}