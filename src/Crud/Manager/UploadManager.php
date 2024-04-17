<?php

namespace App\Crud\Manager;

use App\Entity\Lodging;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadManager extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SluggerInterface       $slugger
    )
    {
    }

    public function uploadOne(
        FormInterface $form,
        object        $object
    ): bool
    {
        $uploaded = false;

        try {
            /** @var UploadedFile $mediaFile */
            $mediaFile = $form->get('media')->getData();

            if ($mediaFile) {
                $savedFile = $this->saveFile($mediaFile);

                $object
                    ->setMediaPath('media/' . $savedFile['newFilename'])
                    ->setMediaSize($savedFile['fileSize']);
                $uploaded = true;
            }
            return $uploaded;
        } catch (FileException $e) {
            throw new FileException($e);
        }
    }

    public function uploadMany(
        FormInterface $form,
        ?object       $object = null
    ): bool
    {
        $mediaFiles = $form->get('photos')->getData();
        $validation = [];

        foreach ($mediaFiles as $mediaFile) {

            if ($mediaFile) {
                $savedFile = $this->saveFile($mediaFile);

                $media = new Media();
                $media
                    ->setMediaPath('media/' . $savedFile['newFilename'])
                    ->setMediaSize($savedFile['fileSize']);

                match (true) {
                    $object instanceof Lodging => $media->addLodging($object),
                    default => ''
                };

                $this->entityManager->persist($media);
                $validation[] = true;
            } else {
                $validation[] = false;
            }

        }
        return !in_array(false, $validation);
    }

    public function saveFile(UploadedFile $mediaFile): array
    {
        $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension();
        $fileSize = $mediaFile->getSize();

        try {

            $mediaFile->move(
                $this->getParameter('media_directory'),
                $newFilename
            );
            return ['newFilename' => $newFilename, 'fileSize' => $fileSize];

        } catch (FileException $e) {
            throw new FileException($e);
        }
    }
}