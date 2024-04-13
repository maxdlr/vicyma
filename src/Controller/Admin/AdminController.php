<?php

namespace App\Controller\Admin;

use App\Crud\AddressCrud;
use App\Repository\AddressRepository;
use App\Service\ClassBrowser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function Sodium\add;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    public function __construct(private readonly AddressCrud $addressCrud, private readonly AddressRepository $addressRepository)
    {
    }

    #[Route(path: '/dashboard', name: 'dashboard', methods: ['GET', 'POST'])]
    public function dashboard(Request $request): Response
    {
        $address = $this->addressRepository->find(30);
        $addressForm = $this->addressCrud->edit($request, $address);

        if ($addressForm === true) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/dashboard.html.twig', [
            'addressForm' => $addressForm->createView(),
        ]);
    }
}