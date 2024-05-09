<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\AfterCrudTrait;
use App\Crud\Manager\CrudSetting;
use App\Crud\Manager\DeleteManager;
use App\Crud\Manager\SaveManager;
use App\Crud\Manager\UploadManager;
use App\Entity\User;
use App\Form\RegistrationType;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: User::class, formType: RegistrationType::class)]
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
        $passwordHasher = $this->passwordHasher;
        return parent::save($request, $object, $options, function ($form, $object) use ($passwordHasher) {

            assert($object instanceof User);

            $plainTextPassword = $object->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $object,
                $plainTextPassword
            );
            $object->setPassword($hashedPassword);

            if ($object->getRoles() === []) $object->setRoles(['ROLE_USER']);

            return true;
        }
        );
    }

    /**
     * Takes the $request and sets the user->isDeleted to true.
     * By default, it then redirects to the referer page.
     *
     * $redirectRoute is an optional string that has to be a valid route name.
     *
     * $redirectParams is an optional array that has to be valid parameters associated with $redirectRoute
     *
     * $doBeforeDelete() is an optional ?callable function that executes before the actual delete.
     * Return array|void
     * It inherits $object, $redirectRoute and $redirectParams.
     * @param callable|null $doBeforeDelete
     * @throws Exception
     *
     * @example fn($object, $redirectRoute, $redirectParams) => {}
     * If it returns void, it executes and delete() continues.
     * If it returns an array, the array can only contain 'save' and|or 'exit'.
     * If it returns 'save', it persists the user object, flushes and delete() continues.
     * If it returns 'exit', it interrupts delete() redirects to $redirectRoute.
     * If it returns both 'save' and 'exit', it will then persist the object, flush, interrupt delete() and redirect to $redirectRoute
     *
     */
    #[Route('user/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $object, string $redirectRoute = 'referer', array $redirectParams = [], ?callable $doBeforeDelete = null): Response
    {
        return $this->deleteManager->delete(
            $request,
            $object,
            $redirectRoute,
            $redirectParams,
            function ($object, $redirectRoute, $redirectParams) use ($doBeforeDelete) {
                assert($object instanceof User);
                $doBeforeDelete($object, $redirectRoute, $redirectParams);
                $this->softDelete($object);
                return ['save', 'exit'];
            }
        );
    }

    private function softDelete(User $user): void
    {
        if (!$user->getIsDeleted()) {
            $user->setIsDeleted(true);
        }
    }
}