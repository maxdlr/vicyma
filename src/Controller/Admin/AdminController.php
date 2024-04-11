<?php

namespace App\Controller\Admin;

use App\Form\AddressType;
use App\Form\BedType;
use App\Form\MediaType;
use App\Form\LodgingType;
use App\Form\MessageType;
use App\Form\ReservationType;
use App\Form\ReviewType;
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
    public function __construct(
        private readonly AddressRepository     $addressRepository,
        private readonly BedRepository         $bedRepository,
        private readonly MediaRepository       $mediaRepository,
        private readonly LodgingRepository     $lodgingRepository,
        private readonly MessageRepository     $messageRepository,
        private readonly ReservationRepository $reservationRepository,
        private readonly ReviewRepository      $reviewRepository,
        private readonly UserRepository        $userRepository,
    )
    {
    }

    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        $user = $this->userRepository->findOneBy(['email' => 'contact@maxdlr.com']);

        $address = $this->addressRepository->find(1);
        $bed = $this->bedRepository->find(1);
        $media = $this->mediaRepository->find(1);
        $lodging = $this->lodgingRepository->find(1);
        $message = $this->messageRepository->find(1);
        $reservation = $this->reservationRepository->find(1);
        $review = $this->reviewRepository->find(1);
        $user = $this->userRepository->find(1);

        $addressForm = $this->createForm(AddressType::class, $address);
        $bedForm = $this->createForm(BedType::class, $bed);
        $mediaForm = $this->createForm(MediaType::class, $media);
        $lodgingForm = $this->createForm(LodgingType::class, $lodging);
        $messageForm = $this->createForm(MessageType::class, $message, [
            'user' => $user
        ]);
        $reservationForm = $this->createForm(ReservationType::class, $reservation, [
            'lodging' => $lodging,
        ]);
        $reviewForm = $this->createForm(ReviewType::class, $review);

        return $this->render('admin/dashboard.html.twig', [
            'addressForm' => $addressForm,
            'bedForm' => $bedForm,
            'mediaForm' => $mediaForm,
            'lodgingForm' => $lodgingForm,
            'messageForm' => $messageForm,
            'reservationForm' => $reservationForm,
            'reviewForm' => $reviewForm,
        ]);
    }
}