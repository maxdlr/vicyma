<?php

namespace App\Crud;

use App\Crud\Manager\AbstractCrud;
use App\Crud\Manager\CrudSetting;
use App\Entity\Bed;
use App\Form\BedType;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[CrudSetting(entity: Bed::class, formType: BedType::class)]
class BedCrud extends AbstractCrud
{
    /**
     * @throws Exception
     */
    #[Route('bed/{id}', name: 'app_bed_delete', methods: ['POST'])]
    public function delete(Request $request, Bed $object): Response
    {
        return $this->deleteManager->delete($request, $object, 'app_home');
    }
}