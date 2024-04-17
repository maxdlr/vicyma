<?php

namespace App\Crud\Manager;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Maxime de la Rocheterie
 */
trait AfterCrudTrait
{
    /**
     * If $url === 'referer', it redirects to the previous (rerefer) page.
     *
     * @param string $url
     * @param Request $request
     * @return Response
     */
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