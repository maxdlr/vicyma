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
     * @param string $routeName
     * @param Request $request
     * @param string $anchor
     * @return Response
     */
    protected function redirectTo(
        string  $routeName,
        Request $request,
        string  $anchor = '',
        array   $routeParams = [],
    ): Response
    {
        $anchorHash = '';
        if ($anchor !== '') {
            $anchorHash = '#' . $anchor;
        }

        if ($routeName === 'referer') {
            return new RedirectResponse($request->headers->get('referer') . $anchorHash, 302);
        }

        $routeName = $this->generateUrl($routeName, $routeParams);

        return new RedirectResponse($routeName, 302);
    }
}