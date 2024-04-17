<?php

namespace App\Crud\Manager;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Maxime de la Rocheterie
 */
class SaveManager extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * $do() inherits some vars: $do($form, $object)
     * @param object $object
     * @param string $formType
     * @param Request $request
     * @param array $options
     * @param callable|null $doBeforeSave
     * @return FormInterface|true
     * @throws Exception
     */
    public function handleAndSave(
        object    $object,
        string    $formType,
        Request   $request,
        array     $options = [],
        ?callable $doBeforeSave = null
    ): FormInterface|true
    {
        $form = $this->createForm($formType, $object, $options);
        $entityManager = $this->entityManager;
        $form->handleRequest($request);
        $saved = false;

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $object = $form->getData();
                if ($doBeforeSave !== null) {
                    if ($doBeforeSave($form, $object, $entityManager) !== true) {
                        return $form;
                    };
                }

                if ($object->getId() !== null) {
                    $object->setUpdatedOn(new DateTime());
                }

                $entityManager->persist($object);
                $entityManager->flush();
                $saved = true;

            } catch (Exception $e) {
                throw new Exception($e);
            }

        }
        return $saved ? true : $form;
    }
}