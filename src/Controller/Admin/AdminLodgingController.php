<?php

namespace App\Controller\Admin;

use App\Crud\LodgingCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Entity\Lodging;
use App\Enum\RoleEnum;
use App\Repository\LodgingRepository;
use App\Repository\ReviewRepository;
use App\Vue\Model\VueDatatableSetting;
use App\Vue\VueFormatter;
use App\Vue\VueObjectMaker;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
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

        if ($lodgingForm === true)
            return $this->redirectTo('referer', $request)->do();

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

        if ($lodgingForm === true) {
            $newLodgingId = $this->lodgingRepository
                ->findOneBy(
                    ['name' => $lodging->getName()],
                    ['createdOn' => 'DESC']
                )->getId();

            return $this->redirectTo('app_admin_lodging_show', null, ['id' => $newLodgingId])->do();
        }

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
        $this->lodgingCrud->delete($request, $lodging);

        return $this->redirectTo('app_admin_management', $request)
            ->withAnchor('lodgings')
            ->do();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws ReflectionException
     * @throws Exception
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
                new VueDatatableSetting(name: 'capacity', values: $capacities, default: '', codeName: 'capacity'),
                new VueDatatableSetting(name: 'name', values: $names, default: '', codeName: 'name'),
                new VueDatatableSetting(name: 'priceByNight', values: $priceByNights, default: '', codeName: 'priceByNight'),
                new VueDatatableSetting(name: 'reviews', values: $reviewRates, default: '', codeName: 'reviews'),
            ],
            items: $lodgings
        )->getAsVueObject();
    }
}