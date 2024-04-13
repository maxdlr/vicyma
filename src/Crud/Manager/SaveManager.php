<?php

namespace App\Crud\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SaveManager extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param object $object
     * @param string $formType
     * @param Request $request
     * @return FormInterface|true
     */
    public function handleAndSave(object $object, string $formType, Request $request): FormInterface|true
    {
        $form = $this->createForm($formType, $object);
        $form->handleRequest($request);
        $saved = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $this->entityManager->persist($object);
            $this->entityManager->flush();
            $saved = true;
        }
        return $saved ? true : $form;
    }
}