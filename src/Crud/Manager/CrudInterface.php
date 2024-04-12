<?php

namespace App\Crud\Manager;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

interface CrudInterface
{
    public function create(Request $request): FormInterface|true;

    public function edit(Request $request, object $object): FormInterface|true;

    #[Route('/{id}', name: '', methods: ['POST'])]
    public function delete(Request $request, object $object): Response;
}