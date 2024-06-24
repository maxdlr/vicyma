<?php

namespace App\Controller\Admin;

use App\Crud\Manager\AfterCrudTrait;
use App\Crud\ReviewCrud;
use App\Entity\Review;
use App\Enum\ReviewStatusEnum;
use App\Enum\RoleEnum;
use App\Repository\LodgingRepository;
use App\Repository\ReviewRepository;
use App\Service\Vue\VueDatatableSetting;
use App\Service\Vue\VueFormatter;
use App\Service\Vue\VueObjectMaker;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/admin/review', name: 'app_admin_review_')]
#[IsGranted(RoleEnum::ROLE_ADMIN->value)]
class AdminReviewController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly ReviewRepository       $reviewRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ReviewCrud             $reviewCrud,
        private readonly LodgingRepository      $lodgingRepository,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        Review  $review,
        Request $request
    ): Response
    {
        $reviewForm = $this->reviewCrud->save($request, $review);

        if ($reviewForm === true) return $this->redirectTo('referer', $request);

        return $this->render('admin/review/review-details.html.twig', [
            'reviewForm' => $reviewForm,
            'review' => $review
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/delete', name: 'delete', methods: ['GET'])]
    public function delete(
        Review  $review,
        Request $request
    ): Response
    {
        $this->entityManager->remove($review);
        $this->entityManager->flush();
        return $this->redirectTo('referer', $request, 'reviews');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws ReflectionException
     */
    public function getNotification(): array
    {
        $pendingReviews = $this->reviewRepository->findBy(['status' => ReviewStatusEnum::PENDING->value]);
        return VueObjectMaker::makeVueObjectOf($pendingReviews, ['id', 'createdOn', 'user', 'rate', 'comment'])->get();
    }

    /**
     * @throws ReflectionException
     */
    public function getData(): array
    {
        $allReviews = $this->reviewRepository->findAll();
        $users = VueObjectMaker::makeVueObjectOf($allReviews, ['user'])->regroup('user')->get();
        $rates = VueObjectMaker::makeVueObjectOf($allReviews, ['rate'])->regroup('rate')->get();
        $lodgings = VueObjectMaker::makeVueObjectOf($this->lodgingRepository->findAll(), ['name'])->regroup('name')->get();
        $publicationDates = VueObjectMaker::makeVueObjectOf($allReviews, ['createdOn'])->regroup('createdOn')->get();
        $reviews = VueObjectMaker::makeVueObjectOf($this->reviewRepository->findAll(),
            [
                'id',
                'rate',
                'user',
                'comment',
                'lodging',
                'createdOn'
            ])->get();

        return VueFormatter::createDatatableComponent(
            name: 'reviews',
            component: 'AdminReviews',
            settings: [
                new VueDatatableSetting('rates', '', $rates, 'rate'),
                new VueDatatableSetting('clients', '', $users, 'user'),
                new VueDatatableSetting('lodging', '', $lodgings, 'lodging'),
                new VueDatatableSetting('publication date', '', $publicationDates, 'createdOn'),
            ],
            items: $reviews
        );
    }
}