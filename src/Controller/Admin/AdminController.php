<?php

namespace App\Controller\Admin;

use App\Crud\AddressCrud;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(private readonly AddressCrud $addressCrud, private readonly AddressRepository $addressRepository)
    {
    }

    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(Request $request): Response
    {
//        $address = $this->addressRepository->find(31);
        $addressForm = $this->addressCrud->create($request);

        if ($addressForm === true) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/dashboard.html.twig', [
            'addressForm' => $addressForm->createView(),
        ]);
    }
}