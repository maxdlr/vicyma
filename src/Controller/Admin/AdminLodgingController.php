<?php

namespace App\Controller\Admin;

use App\Crud\LodgingCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Entity\Lodging;
use App\Enum\RoleEnum;
use App\Repository\LodgingRepository;
use App\Repository\ReviewRepository;
use App\Service\Vue\VueDatatableSetting;
use App\Service\Vue\VueFormatter;
use App\Service\Vue\VueObjectMaker;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/admin/lodging', name: 'app_admin_lodging_')]
#[IsGranted(RoleEnum::ROLE_ADMIN->value)]
class AdminLodgingController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly LodgingRepository      $lodgingRepository,
        private readonly ReviewRepository       $reviewRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LodgingCrud            $lodgingCrud
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        Lodging $lodging,
        Request $request
    ): Response
    {
        $lodgingForm = $this->lodgingCrud->save($request, $lodging);

        if ($lodgingForm === true) return $this->redirectTo('referer', $request);

        return $this->render('admin/lodging/lodging-details.html.twig', [
            'lodgingForm' => $lodgingForm,
            'lodging' => $lodging
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $lodging = new Lodging();
        $lodgingForm = $this->lodgingCrud->save($request, $lodging);

        if ($lodgingForm === true) return $this->redirectTo('app_admin_management', $request, 'lodgings');

        return $this->render('admin/lodging/lodging-new.html.twig', [
            'lodgingForm' => $lodgingForm->createView(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/delete', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(Lodging $lodging, Request $request): Response
    {
        $this->entityManager->remove($lodging);
        $this->entityManager->flush();
        return $this->redirectTo('referer', $request, 'lodgings');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws \ReflectionException
     */
    public function getData(): array
    {
        $allLodgings = $this->lodgingRepository->findAll();

        $capacities = VueObjectMaker::makeVueObjectOf($allLodgings, ['capacity'])->regroup('capacity')->get();
        $names = VueObjectMaker::makeVueObjectOf($allLodgings, ['name'])->regroup('name')->get();
        $priceByNights = VueObjectMaker::makeVueObjectOf($allLodgings, ['priceByNight'])->regroup('priceByNight')->get();
        $reviewRates = VueObjectMaker::makeVueObjectOf($this->reviewRepository->findAll(), ['rate'])->regroup('rate')->get();

        $lodgings = VueObjectMaker::makeVueObjectOf(
            $allLodgings,
            [
                'id',
                'name',
                'capacity',
                'surface',
                'floor',
                'priceByNight',
                'reviews',
                'reservations',
                'beds'
            ]
        )->get();

        return VueFormatter::createDatatableComponent(
            name: 'lodgings',
            component: 'AdminLodgings',
            settings: [
                new VueDatatableSetting('capacity', '', $capacities, 'capacity'),
                new VueDatatableSetting('name', '', $names, 'name'),
                new VueDatatableSetting('priceByNight', '', $priceByNights, 'priceByNight'),
                new VueDatatableSetting('reviews', '', $reviewRates, 'reviews'),
            ],
            items: $lodgings
        );
    }
}