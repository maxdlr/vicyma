<?php

namespace App\Controller\Admin;

use App\Entity\Lodging;
use App\Form\AddressType;
use App\Form\LodgingType;
use App\Repository\AddressRepository;
use App\Repository\BedRepository;
use App\Repository\FileRepository;
use App\Repository\LodgingRepository;
use App\Repository\MessageRepository;
use App\Repository\ReservationRepository;
use App\Repository\ReservationStatusRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly AddressRepository           $addressRepository,
        private readonly BedRepository               $bedRepository,
        private readonly FileRepository              $fileRepository,
        private readonly LodgingRepository           $lodgingRepository,
        private readonly MessageRepository           $messageRepository,
        private readonly ReservationRepository       $reservationRepository,
        private readonly ReservationStatusRepository $reservationStatusRepository,
        private readonly ReviewRepository            $reviewRepository,
        private readonly UserRepository              $userRepository,
    )
    {
    }

    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        $address = $this->addressRepository->find(1);
        $bed = $this->bedRepository->find(1);
        $file = $this->fileRepository->find(1);
        $lodging = $this->lodgingRepository->find(1);
        $message = $this->messageRepository->find(1);
        $reservation = $this->reservationRepository->find(1);
        $reservationStatus = $this->reservationStatusRepository->find(1);
        $review = $this->reviewRepository->find(1);
        $user = $this->userRepository->find(1);

        $addressForm = $this->createForm(AddressType::class);
//        $bedForm = $this->createForm(BedType::class, $bed);
//        $fileForm = $this->createForm(FileType::class, $file);
        $lodgingForm = $this->createForm(LodgingType::class, $lodging);
//        $messageForm = $this->createForm(MessageType::class, $message);
//        $reservationForm = $this->createForm(ReservationType::class, $reservation);
//        $reservationStatusForm = $this->createForm(ReservationStatusType::class, $reservationStatus);
//        $reviewForm = $this->createForm(ReviewType::class, $review);
//        $userForm = $this->createForm(UserType::class, $user);

        return $this->render('admin/dashboard.html.twig', [
            'addressForm' => $addressForm,
//            'bedForm' => $bedForm,
//            'fileForm' => $fileForm,
            'lodgingForm' => $lodgingForm,
//            'messageForm' => $messageForm,
//            'reservationForm' => $reservationForm,
//            'reservationStatusForm' => $reservationStatusForm,
//            'reviewForm' => $reviewForm,
//            'userForm' => $userForm,
        ]);
    }
}