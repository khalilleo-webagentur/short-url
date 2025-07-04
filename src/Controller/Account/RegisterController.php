<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\User;
use App\Entity\UserSetting;
use App\Mails\Account\AccountConfirmationMail;
use App\Service\ProfileService;
use App\Service\SocialProfileSettingService;
use App\Service\TokenGeneratorService;
use App\Service\UserService;
use App\Service\UserSettingService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    use FormValidationTrait;

    private const string HOME_ROUTE = 'app_home';
    private const string LOGIN_ROUTE = 'app_login';
    private const string REGISTER_ROUTE = 'app_register';
    private const string PROFILE_ROUTE = 'app_profile';
    private const string AUTH_ROUTE = 'app_auth';

    public function __construct(
        private readonly UserService                 $userService,
        private readonly TokenGeneratorService       $tokenGeneratorService,
        private readonly UserSettingService          $userSettingService,
        private readonly SocialProfileSettingService $socialProfileSettingService,
        private readonly ProfileService              $profileService
    ) {
    }

    #[Route('/sign-up/q6u3f4y8e7j3z7y6', name: 'app_register')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(self::PROFILE_ROUTE);
        }

        return $this->render('account/register.html.twig');
    }

    #[Route('/register/i9z2x4x4r2k3z3v6', name: 'app_register_new', methods: 'POST')]
    public function new(Request $request, AccountConfirmationMail $accountConfirmationMail): RedirectResponse
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(self::PROFILE_ROUTE);
        }

        $name = $this->validate($request->request->get('name'));
        $email = $this->validateEmail($request->request->get('_username'));
        $confirm = $this->validateCheckbox($request->request->get('confirm'));

        if (!$name || !$email || !$confirm) {
            $this->addFlash('warning', 'All fields are required.');
            return $this->redirectToRoute(self::REGISTER_ROUTE);
        }

        if ($this->userService->getByEmail($email)) {
            $this->addFlash('warning', 'Your account exists. Try to login!');
            return $this->redirectToRoute(self::AUTH_ROUTE);
        }

        $token = $this->tokenGeneratorService->randomTokenForVerification();

        $user = new User();

        $name = preg_replace('/[^a-zA-Z0-9_.]/', '_', $name);

        $this->userService->save(
            $user
                ->setName($name)
                ->setEmail($email)
                ->setPassword($this->userService->encodePassword($email))
                ->setToken($token)
        );

        $accountConfirmationMail->send($name, $email, $token);

        $userSetting = new UserSetting();

        $this->userSettingService->save($userSetting->setUser($user));

        if ($this->socialProfileSettingService->getByName($name)) {
            $name .= '_' . random_int(1111, 9999);
        }

        $this->socialProfileSettingService->add($user, $name);

        $this->profileService->add($user);

        $this->addFlash('notice', 'An email was sent to your mailbox. Please follow instruction to get started.');

        return $this->redirectToRoute(self::HOME_ROUTE);
    }

    #[Route('/verify/email/{token}', name: 'app_register_verify_email')]
    public function verifyUserEmail(?string $token): Response
    {
        $token = $this->validate($token);

        if (empty($token) || strlen($token) !== 32) {
            $this->addFlash('warning', 'Token is not valid.');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        $user = $this->userService->getByToken($token);

        if (!$user) {
            $this->addFlash('warning', 'Your email could not be verified.');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        $this->userService->save(
            $user
                ->setIsVerified(true)
                ->setToken(null)
        );

        $this->addFlash('notice', 'Your email address has been verified.');

        return $this->redirectToRoute(self::AUTH_ROUTE);
    }
}
