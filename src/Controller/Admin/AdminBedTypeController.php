<?php

namespace App\Controller\Admin;

use App\Crud\BedTypeCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Entity\BedType;
use App\Repository\BedTypeRepository;
use App\Repository\ReviewRepository;
use App\Service\VueDataFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/bed', name: 'app_admin_bed_')]
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
        $bedForm = $this->bedTypeCrud->save($request, $bed);
        if ($bedForm === true) return $this->redirectTo('app_admin_management', $request, 'beds');

        return $this->render('admin/bed/bed-details.html.twig', [
            'bedForm' => $bedForm->createView(),
            'bed' => $bed
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $bed = new BedType();
        $bedForm = $this->bedTypeCrud->save($request, $bed);

        if ($bedForm === true) return $this->redirectTo('app_admin_management', $request, 'beds');

        return $this->render('admin/bed/bed-new.html.twig', [
            'bedForm' => $bedForm->createView(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/delete', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(BedType $bed, Request $request): Response
    {
        return $this->bedTypeCrud->delete($request, $bed, 'app_admin_management', anchor: 'beds');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws \ReflectionException
     */
    public function getData(): array
    {
        $allBeds = $this->bedRepository->findAll();

        $beds = VueDataFormatter::makeVueObjectOf(
            $allBeds,
            [
                'id',
                'height',
                'width',
                'isExtra',
                'lodgings'
            ]
        )->get();

        return [
            'name' => 'beds',
            'component' => 'AdminBeds',
            'data' =>
                [
                    'settings' => [],
                    'items' => $beds
                ]
        ];
    }
}