<?php

namespace App\Controller\Admin;

use App\Crud\AdminUserCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Entity\User;
use App\Enum\RoleEnum;
use App\Repository\UserRepository;
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

#[Route(path: '/admin/user', name: 'app_admin_user_')]
#[IsGranted(RoleEnum::ROLE_ADMIN->value)]
class AdminUserController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly AdminUserCrud          $adminUserCrud
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
        $userForm = $this->adminUserCrud->save($request, $user);

        if ($userForm === true) return $this->redirectTo('app_admin_business', $request, 'users');

        return $this->render('admin/user/user-new.html.twig', [
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
        $userForm = $this->adminUserCrud->save($request, $user);

        if ($userForm === true) return $this->redirectTo('referer', $request);

        return $this->render('admin/user/user-details.html.twig', [
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
        return $this->adminUserCrud->delete($request, $user, 'app_admin_business', doBeforeDelete: function ($object) use ($user) {
            assert($object instanceof User);
            $user->setIsDeleted(true);
            return ['save', 'exit'];
        });
    }


    /**
     * @throws ReflectionException
     */
    public function getData(RoleEnum $roleEnum = RoleEnum::ROLE_USER): array
    {
        $allUsers = $this->userRepository->findByRole($roleEnum);
        $firstnames = VueObjectMaker::makeVueObjectOf($allUsers, ['firstname'])->regroup('firstname')->get();
        $lastnames = VueObjectMaker::makeVueObjectOf($allUsers, ['lastname'])->regroup('lastname')->get();
        $isDeleted = VueObjectMaker::makeVueObjectOf($allUsers, ['isDeleted'])->regroup('isDeleted')->get();
        $creationDate = VueObjectMaker::makeVueObjectOf($allUsers, ['createdOn'])->regroup('createdOn')->get();
        $users = VueObjectMaker::makeVueObjectOf($allUsers,
            [
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
                new VueDatatableSetting('lastname', '', $lastnames, 'lastname'),
                new VueDatatableSetting('firstname', '', $firstnames, 'firstname'),
                new VueDatatableSetting('is deleted', false, $isDeleted, 'isDeleted'),
                new VueDatatableSetting('member since', '', $creationDate , 'createdOn')
            ],
            items: $users
        );
    }
}