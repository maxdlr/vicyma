<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Crud\Manager\DeleteManager;
use App\Crud\Manager\SaveManager;
use App\Entity\Media;
use App\Form\MediaType;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[CrudSetting(entity: Media::class, formType: MediaType::class)]
class MediaCrud extends AbstractCrud
{
    public function __construct(
        SaveManager                       $saveManager,
        DeleteManager                     $deleteManager,
        private readonly SluggerInterface $slugger,
    )
    {
        parent::__construct($saveManager, $deleteManager);
    }

    /**
     * @throws Exception
     */
    public function create(Request $request): FormInterface|true
    {
        $object = new $this->entity();
        return $this->saveManager->handleAndSave(
            $object,
            $this->formType,
            $request,
            fn($form, $object) => $this->saveManager->upload($form, $object, $this->slugger)
        );
    }

    /**
     * @throws Exception
     */
    public function edit(Request $request, object $object): FormInterface|true
    {
        return $this->saveManager->handleAndSave(
            $object,
            $this->formType,
            $request,
            fn($form, $object) => $this->saveManager->upload($form, $object, $this->slugger)
        );
    }

    #[Route('media/{id}', name: 'app_media_delete', methods: ['POST'])]
    public function delete(Request $request, Media $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'app_home');
    }
}