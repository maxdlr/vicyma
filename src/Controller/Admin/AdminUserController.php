<?php

namespace App\Controller\Admin;

use App\Crud\AdminUserCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\VueDataFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/user', name: 'app_admin_user_')]
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
    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        User    $user,
        Request $request
    ): Response
    {
        $userForm = $this->adminUserCrud->save($request, $user);

        if ($userForm === true) return $this->redirectTo('referer', $request);

        return $this->render('admin/show/user-details.html.twig', [
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
        $user->setIsDeleted(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @throws ReflectionException
     */
    public function getUserData(): array
    {
        $firstnames = VueDataFormatter::makeVueObjectOf(
            $this->userRepository->findAll(), ['firstname']
        )->regroup('firstname')->get();

        $lastnames = VueDataFormatter::makeVueObjectOf(
            $this->userRepository->findAll(), ['lastname']
        )->regroup('lastname')->get();

        $users = VueDataFormatter::makeVueObjectOf(
            $this->userRepository->findBy(['isDeleted' => false]),
            [
                'id',
                'firstname',
                'lastname',
                'email',
                'phoneNumber',
                'reservations',
            ])->get();

        return [
            'settings' => [
                'firstname' => ['name' => 'first name', 'default' => '', 'values' => $firstnames, 'codeName' => 'firstname'],
                'lastname' => ['name' => 'last name', 'default' => '', 'values' => $lastnames, 'codeName' => 'lastname'],
            ],
            'items' => $users
        ];
    }
}