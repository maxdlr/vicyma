<?php

namespace App\Controller\Admin;

use App\Crud\ReservationCrud;
use App\Entity\Reservation;
use App\Repository\LodgingRepository;
use App\Repository\ReservationRepository;
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
        private readonly ReservationCrud       $reservationCrud,
        private readonly ReservationRepository $reservationRepository,
        private readonly LodgingRepository     $lodgingRepository,
        private readonly UserRepository        $userRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(Request $request): Response
    {
        $lodging = $this->lodgingRepository->find(1);
        $user = $this->userRepository->find(1);
        $reservation = $this->reservationRepository->find(2);
//        $reservation = new Reservation();
        $reservationForm = $this->reservationCrud->save($request, $reservation, ['lodging' => $lodging, 'user' => $user]);

        if ($reservationForm === true) {
            return $this->redirectToRoute('app_home');
        }

//        dump($reservation);

        return $this->render('admin/dashboard.html.twig', [
            'reservationForm' => $reservationForm->createView(),
        ]);
    }
}