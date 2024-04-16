<?php

namespace App\Controller\Admin;

use App\Crud\ReviewCrud;
use App\Entity\Review;
use App\Repository\LodgingRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly ReviewCrud        $reviewCrud,
        private readonly ReviewRepository  $reviewRepository,
        private readonly LodgingRepository $lodgingRepository,
        private readonly UserRepository    $userRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(Request $request): Response
    {
        $lodging = $this->lodgingRepository->find(5);
        $user = $this->userRepository->find(1);

//        $review = new Review();
        $review = $this->reviewRepository->find(1);
        $reviewForm = $this->reviewCrud->save($request, $review, ['lodging' => $lodging, 'user' => $user]);

        if ($reviewForm === true) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/dashboard.html.twig', [
            'reviewForm' => $reviewForm->createView(),
        ]);
    }
}