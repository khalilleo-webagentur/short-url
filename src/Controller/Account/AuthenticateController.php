<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Mails\Account\HandelTwoFactorAuthMail;
use App\Service\TokenGeneratorService;
use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticateController extends AbstractController
{
    use FormValidationTrait;

    public function __construct(
        private readonly UserService $userService,
        private readonly TokenGeneratorService $tokenGeneratorService,
        private readonly RequestStack $requestStack
    ) {
    }

    #[Route('/auth/tU2oH0oT4kL1wW5oN7jO0mD6eS2qY5wU', name: 'app_auth')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profile');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/auth.html.twig', [
            'email' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/auth/send/qB6zN6bD7wK2bN4mP0sF6sC8dO5kR0eJ', name: 'app_auth_send')]
    public function send(Request $request, HandelTwoFactorAuthMail $handelTwoFactorAuthMail): RedirectResponse
    {
        $email = $this->validateEmail($request->request->get('_username'));

        if (!$email) {
            $this->addFlash('warning', 'Email field is required.');
            return $this->redirectToRoute('app_auth');
        }

        $user = $this->userService->getByEmail($email);

        if (!$user) {
            $this->addFlash('warning', 'If your email exists then a new OTP just being sent to your email.');
            return $this->redirectToRoute('app_home');
        }

        if ($user && !$user->isVerified()) {
            $this->addFlash('warning', 'Your account is not verified yet. Please verify your email.');
            return $this->redirectToRoute('app_home');
        }

        $otp = $this->tokenGeneratorService->randomToken();

        $this->userService->save($user->setToken($otp));

        $handelTwoFactorAuthMail->send($user->getName(), $email, $otp);

        // $session = $this->requestStack->getSession();
        // $session->set(SessionHelper::USER_SESSION_KEY . $user->getId(), sha1($user->getUserIdentifier()));

        $this->addFlash('notice', 'A new OTP is being sent to your email.');

        return $this->redirectToRoute('app_login');
    }
}
