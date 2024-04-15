<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Media;
use App\Form\MediaType;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Media::class, formType: MediaType::class)]
class MediaCrud extends AbstractCrud
{
    /**
     * @param Request $request
     * @param object $object
     * @param array $options
     * @return FormInterface|true
     * @throws Exception
     */
    public function save(Request $request, object $object, array $options = []): FormInterface|true
    {
        return $this->saveManager->handleAndSave(
            $object,
            $this->formType,
            $request,
            do: fn($form, $object) => $this->uploadManager->uploadOne($form, $object)
        );
    }

    #[Route('media/{id}', name: 'app_media_delete', methods: ['POST'])]
    public function delete(Request $request, Media $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'referer');
    }
}