<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Crud\Manager\DeleteManager;
use App\Crud\Manager\SaveManager;
use App\Crud\Manager\UploadManager;
use App\Entity\Lodging;
use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewType;
use DateTime;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Review::class, formType: ReviewType::class)]
class ReviewCrud extends AbstractCrud
{
    public function __construct(SaveManager $saveManager, DeleteManager $deleteManager, UploadManager $uploadManager)
    {
        parent::__construct($saveManager, $deleteManager, $uploadManager);
    }

    public function save(Request $request, object $object, array $options = []): FormInterface|true
    {
        return $this->saveManager->handleAndSave(
            $object,
            $this->formType,
            $request,
            $options,
            function ($form, $object) use ($options) {
                $lodging = $options['lodging'];
                $user = $options['user'];

                assert($lodging instanceof Lodging);
                assert($user instanceof User);
                assert($object instanceof Review);

                $object
                    ->setPublishedOn(new DateTime())
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