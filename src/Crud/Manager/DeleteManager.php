<?php

namespace App\Crud\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Maxime de la Rocheterie
 */
class DeleteManager extends AbstractController
{
    use AfterCrudTrait;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * Takes the $request and deletes the object from the database.
     * By default, it just deletes the object as is and redirects to the referer page.
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
     * If it returns 'save', it persists the object, flushes and delete() continues.
     * If it returns 'exit', it interrupts delete() redirects to $redirectRoute.
     * If it returns both 'save' and 'exit', it will then persist the object, flush, interrupt delete() and redirect to $redirectRoute
     *
     */
    public function delete(
        Request  $request,
        object   $object,
        string   $redirectRoute,
        array    $redirectParams,
        string   $anchor = '',
        callable $doBeforeDelete = null
    ): Response
    {
//        if ($redirectRoute !== 'referer') {
//            $redirectRoute = $this->generateUrl($redirectRoute, $redirectParams);
//        }

        if ($this->isCsrfTokenValid('delete' . $object->getId(), $request->getPayload()->get('_token'))) {
            if ($doBeforeDelete !== null) {

                $do = $doBeforeDelete($object, $redirectRoute, $redirectParams);
                try {
                    if (in_array('save', $do)) {
                        $this->entityManager->persist($object);
                        $this->entityManager->flush();
                    }
                    if (in_array('exit', $do)) return $this->redirectTo($redirectRoute, $request);
                } catch (Exception) {
                    throw new Exception('Cannot delete this object: ' . $object->getid());
                }
            }

            $this->entityManager->remove($object);
            $this->entityManager->flush();
        }

        return $this->redirectTo($redirectRoute, $request, $anchor);
    }
}