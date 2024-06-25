<?php

namespace App\Crud\Manager;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Maxime de la Rocheterie
 */
trait AfterCrudTrait
{
    private ?string $redirectionUrl = null;
    private ?string $routeName = null;
    const ROUTES_WITH_HASHES = [
        'app_user_account_conversation_inbox',
        'app_admin_business',
        'app_admin_management',
    ];

    protected function redirectTo(
        string $routeName,
        Request $request = null,
        array $routeParams = []
    ): self
    {
        if ($routeName === 'referer') {
            $this->routeName = $request->attributes->get('_route');
            $this->redirectionUrl = $this->getReferer($request);
        } else {
            $this->routeName = $routeName;
            $this->redirectionUrl = $this->getRequestedRedirection($routeName, $routeParams);
        }

        return $this;
    }

    protected function withAnchor(string $anchor): self
    {
        assert($this->routeName !== null);
        assert($this->doesRouteUseHashes(), 'This route does use hashes/anchors');
        $this->redirectionUrl .= $this->getHash($anchor);

        return $this;
    }

    protected function do(): RedirectResponse
    {
        return new RedirectResponse($this->redirectionUrl, 302);
    }

    private function doesRouteUseHashes(): bool
    {
        return in_array($this->routeName, self::ROUTES_WITH_HASHES);
    }

    private function getHash(string $anchor = ''): string
    {
        return $anchor !== '' ? '#' . $anchor : '';
    }

    private function getRequestedRedirection(
        string $routeName,
        array $routeParams
    ): string
    {
        return $this->generateUrl($routeName, $routeParams);
    }

    private function getReferer(Request $request): string
    {
        assert($request !== null, 'Cannot redirect to referer without Request');
        return $request->headers->get('referer');
    }
}