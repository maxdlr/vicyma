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


    #[Route('review/{id}', name: 'app_review_delete', methods: ['POST'])]
    public function delete(Request $request, Review $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'app_home');
    }
}