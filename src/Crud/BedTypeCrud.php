<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\BedType;
use App\Form\BedTypeType;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: BedType::class, formType: BedTypeType::class)]
class BedTypeCrud extends AbstractCrud
{
    /**
     * Takes the $request and deletes the bed object from the database.
     * By default, it just deletes the bed object as is and redirects to the referer page.
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
     * If it returns an array, the array can only contain 'save' or 'exit'.
     * If it returns 'save', it persists the bed object, flushes and delete() continues.
     * If it returns 'exit', it interrupts delete() redirects to $redirectRoute.
     * If it returns both 'save' and 'exit', it will then persist the object, flush, interrupt delete() and redirect to $redirectRoute
     *
     */
    #[Route('bed/{id}', name: 'app_bed_delete', methods: ['POST'])]
    public function delete(Request $request, BedType $object, string $redirectRoute = 'referer', array $redirectParams = [], string $anchor = '', ?callable $doBeforeDelete = null): Response
    {
        return $this->deleteManager->delete($request, $object, $redirectRoute, $redirectParams, $anchor, $doBeforeDelete);
    }
}