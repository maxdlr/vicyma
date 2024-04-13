<?php

namespace App\Crud\Manager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CrudInterface
{
    public function delete(Request $request, object $object): Response;
}