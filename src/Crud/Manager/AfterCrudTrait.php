<?php

namespace App\Crud\Manager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait AfterCrudTrait
{
    protected function redirectTo(
        string  $url,
        Request $request,
    ): Response
    {
        if ($url === 'referer') {
            return new RedirectResponse($request->headers->get('referer'), 302);
        }

        return new RedirectResponse($url, 302);
    }
}