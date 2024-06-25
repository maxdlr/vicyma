<?php

namespace App\Controller\User;

use App\Crud\AddressCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Entity\Address;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserAddressController extends AbstractController
{
    use AfterCrudTrait;
    public function __construct(
        private readonly AddressCrud $addressCrud
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('address/{id}', name: 'app_address_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Address $address
    ): RedirectResponse
    {
        $this->addressCrud->delete($request, $address);
        return $this->redirectTo('referer', $request)->do();
    }
}