<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Crud\Manager\CrudSetting;
use App\Crud\Manager\DeleteManager;
use App\Crud\Manager\SaveManager;
use App\Crud\Manager\UploadManager;
use App\Entity\User;
use App\Form\AdminUserType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[CrudSetting(entity: User::class, formType: AdminUserType::class)]
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

    public function save(Request $request, object $object, array $options = [], ?callable $doBeforeSave = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object) {
            assert($object instanceof User);
            $object->setPassword($this->hashPassword($object, $object->getPassword()));
            if ($object->getRoles() === []) $object->setRoles(['ROLE_USER']);
            return true;
        }
        );
    }

    private function hashPassword(User $user, string $plainTextPassword): string
    {
        return $this->passwordHasher->hashPassword(
            $user,
            $plainTextPassword
        );
    }
}