<?php

namespace App\Controller\Website;

use App\Repository\LodgingRepository;
use App\Vue\Model\VueDatatableSetting;
use App\Vue\VueFormatter;
use App\Vue\VueObjectMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly LodgingRepository $lodgingRepository
    )
    {
    }

    /**
     * @throws \ReflectionException
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $allLodgings = $this->lodgingRepository->findAll();

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
                ['medias', 'name', 'surface', 'roomCount', 'terraceSurface', 'priceByNight', 'floor', 'roomCount', 'capacity']
            )->get()
        );

        return $this->render('home/index.html.twig', [
            'lodgings' => $lodgings
        ]);
    }
}