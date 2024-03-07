<?php

namespace App\Controller;

use App\Repository\LodgingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LodgingController extends AbstractController
{
    public function __construct(
        private readonly LodgingRepository $lodgingRepository,
    )
    {
    }

    #[Route(path: '/', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        $lodgings = $this->lodgingRepository->findAll();

        return $this->render('home/index.html.twig', [
            'lodgings' => $lodgings
        ]);
    }
}