<?php

namespace App\Crud\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteManager extends AbstractController
{
    use AfterCrudTrait;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws Exception
     */
    public function delete(
        Request  $request,
        object   $object,
        string   $redirectRoute = 'referer',
        array    $redirectParams = [],
        callable $doBeforeDelete = null
    ): Response
    {
        if ($redirectRoute !== 'referer') {
            $redirectRoute = $this->generateUrl($redirectRoute, $redirectParams);
        }

        if ($this->isCsrfTokenValid('delete' . $object->getId(), $request->getPayload()->get('_token'))) {

            if ($doBeforeDelete !== null) {
                $do = $doBeforeDelete($object, $redirectRoute, $redirectParams);
                try {
                    if (in_array('persist', $do)) $this->entityManager->persist($object);
                    if (in_array('flush', $do)) $this->entityManager->flush();
                    if (in_array('exit', $do)) return $this->redirectTo($redirectRoute, $request);
                } catch (Exception) {
                    throw new Exception('Cannot delete this object: ' . $object->getid());
                }
            }

            $this->entityManager->remove($object);
            $this->entityManager->flush();
        }

        return $this->redirectTo($redirectRoute, $request);
    }
}