<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Lodging;
use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Review::class, formType: ReviewType::class)]
class ReviewCrud extends AbstractCrud
{
    public function save(Request $request, object $object, array $options = [], ?callable $doBeforeSave = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object) use ($options) {
            $lodging = $options['lodging'];
            $user = $options['user'];

            assert($lodging instanceof Lodging);
            assert($user instanceof User);
            assert($object instanceof Review);

            $object
                ->setUser($user)
                ->setLodging($lodging);

            return true;
        }
        );
    }

    /**
     * Takes the $request and deletes the review object from the database.
     * By default, it just deletes the review object as is and redirects to the referer page.
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
     * If it returns 'save', it persists the review object, flushes and delete() continues.
     * If it returns 'exit', it interrupts delete() redirects to $redirectRoute.
     *
     */
    #[Route('review/{id}', name: 'app_review_delete', methods: ['POST'])]
    public function delete(Request $request, Review $object, string $redirectRoute = 'referer', array $redirectParams = [], ?callable $doBeforeDelete = null): Response
    {
        return $this->deleteManager->delete($request, $object, $redirectRoute, $redirectParams, $doBeforeDelete);
    }
}