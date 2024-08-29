<?php

namespace App\Controller\Admin;

use App\Crud\BedTypeCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Entity\BedType;
use App\Enum\RoleEnum;
use App\Repository\BedTypeRepository;
use App\Repository\ReviewRepository;
use App\Vue\Model\VueDatatableSetting;
use App\Vue\VueFormatter;
use App\Vue\VueObjectMaker;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/admin/bed', name: 'app_admin_bed_')]
#[IsGranted(RoleEnum::ROLE_ADMIN->value)]
class AdminBedTypeController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly BedTypeRepository      $bedRepository,
        private readonly ReviewRepository       $reviewRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly BedTypeCrud            $bedTypeCrud
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        BedType $bed,
        Request $request
    ): Response
    {
        $bedForm = $this->bedTypeCrud->save(request: $request, object: $bed);
        if ($bedForm === true) return $this->redirectToManagementUsers();

        return $this->render(view: 'admin/bed/bed-details.html.twig', parameters: [
            'bedForm' => $bedForm->createView(),
            'bed' => $bed
        ]);
    }

    public function redirectToManagementUsers(): RedirectResponse
    {
        return $this->redirectTo(routeName: 'app_admin_management')
            ->withAnchor('beds')->do();
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $bed = new BedType();
        $bedForm = $this->bedTypeCrud->save(request: $request, object: $bed);

        if ($bedForm === true) return $this->redirectToManagementUsers();

        return $this->render(view: 'admin/bed/bed-new.html.twig', parameters: [
            'bedForm' => $bedForm->createView(),
        ]);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/delete', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(BedType $bed, Request $request): Response
    {
        $this->bedTypeCrud->delete(request: $request, object: $bed);
        return $this->redirectToManagementUsers();
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     * @throws Exception
     */
    public function getData(): array
    {
        $allBeds = $this->bedRepository->findAll();

        $beds = VueObjectMaker::makeVueObjectOf(
            entities: $allBeds, properties: ['id', 'height', 'width', 'isExtra', 'lodgings']
        )->get();
        $widths = VueObjectMaker::makeVueObjectOf($allBeds, ['width'])->regroup('width')->get();
        $heights = VueObjectMaker::makeVueObjectOf($allBeds, ['height'])->regroup('height')->get();

        return VueFormatter::createDatatableComponent(
            name: 'beds',
            component: 'AdminBeds',
            settings: [
                new VueDatatableSetting(name: 'width', values: $widths, default: '', codeName: 'width'),
                new VueDatatableSetting(name: 'height', values: $heights, default: '', codeName: 'height'),
            ],
            items: $beds
        )->getAsVueObject();
    }
}