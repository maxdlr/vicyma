<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Address::class, formType: AddressType::class)]
class AddressCrud extends AbstractCrud
{
    public function save(Request $request, object $object, array $options = [], ?callable $do = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object, $entityManager) use ($options) {
            $user = $options['user'];
            assert($object instanceof Address);
            assert($user instanceof User);
            assert($entityManager instanceof EntityManagerInterface);
            $user->setAddress($object);
            return true;
        });
    }


    /**
     * @throws Exception
     */
    #[Route('address/{id}', name: 'app_address_delete', methods: ['POST'])]
    public function delete(Request $request, Address $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'app_home');
    }
}