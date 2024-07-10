<?php

namespace App\Controller\Website;

use App\Repository\LodgingRepository;
use App\Repository\MediaRepository;
use App\Vue\Model\VueDatatableSetting;
use App\Vue\VueFormatter;
use App\Vue\VueObjectMaker;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly LodgingRepository $lodgingRepository,
        private readonly MediaRepository $mediaRepository,
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $allLodgings = $this->lodgingRepository->findAll();
        $allMedia = $this->mediaRepository->findAll();

        $lodgings = VueFormatter::createDatatable(
            settings: [
                new VueDatatableSetting(
                    'beds',
                    VueObjectMaker::makeVueObjectOf(
                        $allLodgings,
                        ['capacity']
                    )->regroup('capacity')->get(),
                    '',
                    'capacity'
                ),
                new VueDatatableSetting(
                    'rooms',
                    VueObjectMaker::makeVueObjectOf(
                        $allLodgings,
                        ['roomCount']
                    )->regroup('roomCount')->get(),
                    '',
                    'roomCount'
                ),
                new VueDatatableSetting(
                    'floor',
                    VueObjectMaker::makeVueObjectOf(
                        $allLodgings,
                        ['floor']
                    )->regroup('floor')->get(),
                    '',
                    'floor'
                )
            ],
            items: VueObjectMaker::makeVueObjectOf(
                $allLodgings,
                ['medias', 'name', 'surface', 'roomCount', 'terraceSurface', 'priceByNight', 'floor', 'roomCount', 'capacity', 'description']
            )->get()
        );

        //todo: make headerBackground something chosen rather than random
        $headerBackground = VueObjectMaker::makeVueObjectOf(
            [$allMedia[rand(0, count($allMedia) - 1)]],
            ['mediaPath']
        )->get()[0];

        return $this->render('home/index.html.twig', [
            'lodgings' => $lodgings,
            'headerBackground' => $headerBackground
        ]);
    }
}