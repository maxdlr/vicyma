<?php

namespace App\Crud\Manager;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class SaveManager extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @param object $object
     * @param string $formType
     * @param Request $request
     * @param callable|null $do
     * @return FormInterface|true
     * @throws Exception
     */
    public function handleAndSave(
        object    $object,
        string    $formType,
        Request   $request,
        ?callable $do = null
    ): FormInterface|true
    {
        $form = $this->createForm($formType, $object);
        $form->handleRequest($request);
        $saved = false;

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                if ($do === null) {
                    $object = $form->getData();
                } else {
                    $object = $do($form, $object);
                }

                $this->entityManager->persist($object);
                $this->entityManager->flush();
                $saved = true;

            } catch (Exception $e) {
                throw new Exception($e);
            }

        }
        return $saved ? true : $form;
    }
}