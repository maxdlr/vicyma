<?php

namespace App\Controller\Admin;

use App\Crud\UserCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Entity\User;
use App\Enum\RoleEnum;
use App\Repository\UserRepository;
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

#[Route(path: '/admin/user', name: 'app_admin_user_')]
#[IsGranted(RoleEnum::ROLE_ADMIN->value)]
class AdminUserController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserCrud $userCrud
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $userForm = $this->userCrud->save(request: $request, object: $user);

        if ($userForm === true) return $this->redirectToBusinessUsers($request);

        return $this->render(view: 'admin/user/user-new.html.twig', parameters: [
            'userForm' => $userForm->createView(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        User    $user,
        Request $request
    ): Response
    {
        $userForm = $this->userCrud->save(request: $request, object: $user);
        if ($userForm === true) return $this->redirectToBusinessUsers($request);

        return $this->render(view: 'admin/user/user-details.html.twig', parameters: [
            'userForm' => $userForm,
            'user' => $user
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/delete', name: 'delete', methods: ['GET'])]
    public function delete(
        User    $user,
        Request $request
    ): Response
    {
        $this->userCrud->delete($request, $user, function($object) {
            if (!$object->getIsDeleted()) {
                $object->setIsDeleted(true);
            }
            return ['save', 'exit'];
        });

        if (str_contains($request->headers->get('referer'), 'management')) {
            return $this->redirectTo('app_admin_management')
                ->withAnchor('users')
                ->do();
        } else {
            return $this->redirectToBusinessUsers($request);
        }
    }

    private function redirectToBusinessUsers(Request $request): RedirectResponse
    {
        return $this->redirectTo(
            routeName: 'app_admin_business',
            request: $request)
            ->withAnchor(anchor: 'users')
            ->do();
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function getData(RoleEnum $roleEnum = RoleEnum::ROLE_USER): array
    {
        $allUsers = $this->userRepository->findByRole($roleEnum);
        $firstnames = VueObjectMaker::makeVueObjectOf(entities: $allUsers, properties: ['firstname'])->regroup('firstname')->get();
        $lastnames = VueObjectMaker::makeVueObjectOf(entities: $allUsers, properties: ['lastname'])->regroup('lastname')->get();
        $isDeleted = VueObjectMaker::makeVueObjectOf(entities: $allUsers, properties: ['isDeleted'])->regroup('isDeleted')->get();
        $creationDate = VueObjectMaker::makeVueObjectOf(entities: $allUsers, properties: ['createdOn'])->regroup('createdOn')->get();
        $users = VueObjectMaker::makeVueObjectOf(entities: $allUsers,
            properties: [
                'id',
                'firstname',
                'lastname',
                'email',
                'phoneNumber',
                'reservations',
                'isDeleted',
                'address',
                'createdOn',
            ])->get();

        return VueFormatter::createDatatableComponent(
            name: 'users',
            component: 'AdminUsers',
            settings: [
                new VueDatatableSetting(name: 'lastname', values: $lastnames, default: '', codeName: 'lastname'),
                new VueDatatableSetting(name: 'firstname', values: $firstnames, default: '', codeName: 'firstname'),
                new VueDatatableSetting(name: 'is deleted', values: $isDeleted, default: false, codeName: 'isDeleted'),
                new VueDatatableSetting(name: 'member since', values: $creationDate, default: '', codeName: 'createdOn')
            ],
            items: $users
        )->getAsVueObject();
    }
}