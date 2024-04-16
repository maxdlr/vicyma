<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Crud\Manager\CrudSetting;
use App\Crud\Manager\DeleteManager;
use App\Crud\Manager\SaveManager;
use App\Crud\Manager\UploadManager;
use App\Entity\User;
use App\Form\UserType;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: User::class, formType: UserType::class)]
class UserCrud extends AbstractCrud
{
    use AfterCrudTrait;

    public function __construct(
        SaveManager                                  $saveManager,
        DeleteManager                                $deleteManager,
        UploadManager                                $uploadManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct($saveManager, $deleteManager, $uploadManager);
    }

    public function save(Request $request, object $object, array $options = [], ?callable $do = null): FormInterface|true
    {
        $passwordHasher = $this->passwordHasher;
        return parent::save($request, $object, $options, function ($form, $object) use ($passwordHasher) {

            assert($object instanceof User);

            $plainTextPassword = $object->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $object,
                $plainTextPassword
            );
            $object->setPassword($hashedPassword);
            $object->setRoles(['ROLE_USER']);

            return true;
        }
        );
    }


    /**
     * @throws Exception
     */
    #[Route('user/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $object): Response
    {
        return $this->deleteManager->delete(
            $request,
            $object,
            'app_home',
            [],
            function ($object) use ($request) {
                assert($object instanceof User);
                $object->setIsDeleted(true);
                return ['persist', 'flush', 'exit'];
            }
        );
    }
}