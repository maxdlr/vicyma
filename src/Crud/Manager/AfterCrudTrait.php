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
     * @param Request|null $request
     * @param string $anchor
     * @param array $routeParams
     * @return Response
     */
    protected function redirectTo(
        string  $routeName,
        Request $request = null,
        string  $anchor = '',
        array   $routeParams = [],
    ): Response
    {
        $anchorHash = '';
        if ($anchor !== '') {
            $anchorHash = '#' . $anchor;
        }

        if ($routeName === 'referer' && $request !== null) {
            return new RedirectResponse($request->headers->get('referer'), 302);
        }

        $routeName = $this->generateUrl($routeName, $routeParams) . $anchorHash;


        return new RedirectResponse($routeName, 302);
    }
}