<?php

namespace App\Crud\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteManager extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function delete(Request $request, object $object, string $redirectRoute): Response
    {
        if ($this->isCsrfTokenValid('delete' . $object->getId(), $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($object);
            $this->entityManager->flush();
        }

        if ($redirectRoute === 'referer') {
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->redirectToRoute($redirectRoute);
    }
}