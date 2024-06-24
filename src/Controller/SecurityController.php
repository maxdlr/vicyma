<?php

namespace App\Controller;

use App\Enum\RoleEnum;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly UserManager $userManager
    )
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/login/success', name: 'app_login_success')]
    public function loginSuccess(): RedirectResponse
    {
        $user = $this->userManager->user;

        return in_array(RoleEnum::ROLE_ADMIN->value, $user->getRoles()) ?
            $this->redirectToRoute('app_admin_dashboard') :
            $this->redirectToRoute('app_user_account_dashboard', ['id' => $user->getId()]);
    }


}
