<?php

namespace App\Crud\Manager;

use App\Entity\Lodging;
use App\Entity\Media;
use DateTime;
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
    ): void
    {
        /** @var UploadedFile $mediaFile */
        $mediaFile = $form->get('media')->getData();

        if ($mediaFile) {
            $savedFile = $this->saveFile($mediaFile);

            $object
                ->setMediaPath('media/' . $savedFile['newFilename'])
                ->setMediaSize($savedFile['fileSize'])
                ->setCreatedOn(new DateTime('now'));
        }
    }

    public function uploadMany(
        FormInterface $form,
        object        $object
    ): void
    {
        $mediaFiles = $form->get('photos')->getData();

        foreach ($mediaFiles as $mediaFile) {
            if ($mediaFile) {
                $savedFile = $this->saveFile($mediaFile);
                assert($object instanceof Lodging);

                $media = new Media();
                $media
                    ->setMediaPath('media/' . $savedFile['newFilename'])
                    ->setMediaSize($savedFile['fileSize'])
                    ->setCreatedOn(new DateTime('now'))
                    ->addLodging($object);

                $this->entityManager->persist($media);
            }
        }
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
        } catch (FileException $e) {
            throw new FileException($e);
        }

        return ['newFilename' => $newFilename, 'fileSize' => $fileSize];
    }
}