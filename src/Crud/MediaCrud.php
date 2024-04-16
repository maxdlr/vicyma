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
    public function save(Request $request, object $object, array $options = [], ?callable $do = null): FormInterface|true
    {
        return parent::save($request, $object, $options, function ($form, $object) {
            return $this->uploadManager->uploadOne($form, $object);
        });
    }

    #[Route('media/{id}', name: 'app_media_delete', methods: ['POST'])]
    public function delete(Request $request, Media $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'referer');
    }
}