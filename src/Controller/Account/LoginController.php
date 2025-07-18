<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    private const string AUTHENTICATE_ROUTE = 'app_auth';

    public function __construct(
        private readonly UserService $userService
    ) {
    }

    #[Route('/login/s7t3a1k3b6d5n1v6', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profile');
        }

        if (!$this->userService->hasUserRequestedNewSecurityCode()) {
            return $this->redirectToRoute(self::AUTHENTICATE_ROUTE);
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'email' => $lastUsername,
            'error' => $error
        ]);
    }
}
