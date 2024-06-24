<?php

namespace App\Controller\Admin;

use App\Crud\AddressCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Entity\Address;
use App\Enum\RoleEnum;
use App\Repository\AddressRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/admin/address', name: 'app_admin_address_')]
#[IsGranted(RoleEnum::ROLE_ADMIN->value)]
class AdminAddressController extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(
        private readonly AddressRepository      $addressRepository,
        private readonly ReviewRepository       $reviewRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly AddressCrud            $addressCrud
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/show', name: 'show', methods: ['GET', 'POST'])]
    public function show(
        Address $address,
        Request $request
    ): Response
    {
        $addressForm = $this->addressCrud->save($request, $address);

        if ($addressForm === true) return $this->redirectTo('referer', $request);

        return $this->render('admin/address/address-details.html.twig', [
            'addressForm' => $addressForm,
            'address' => $address
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $address = new Address();
        $addressForm = $this->addressCrud->save($request, $address);

        if ($addressForm === true) return $this->redirectTo('app_admin_management', $request, 'addresss');

        return $this->render('admin/address/address-new.html.twig', [
            'addressForm' => $addressForm->createView(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/{id}/delete', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(Address $address, Request $request): Response
    {
        return $this->addressCrud->delete($request, $address, 'app_admin_business');
    }
}