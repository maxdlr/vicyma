<?php

namespace App\Controller\Admin;

use App\Crud\AddressCrud;
use App\Entity\Address;
use App\Repository\AddressRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly AddressCrud       $addressCrud,
        private readonly AddressRepository $addressRepository,
        private readonly UserRepository    $userRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(Request $request): Response
    {
        $address = new Address();
        $user = $this->userRepository->find(1);
//        $address = $this->addressRepository->findOneBy(['isDeleted' => false]);
        $addressForm = $this->addressCrud->save($request, $address, ['user' => $user]);

        if ($addressForm === true) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/dashboard.html.twig', [
            'addressForm' => $addressForm->createView(),
        ]);
    }
}