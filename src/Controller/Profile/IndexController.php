<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Service\LinkService;
use App\Service\ProfileService;
use App\Service\SocialProfileSettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly ProfileService $profileService,
        private readonly LinkService $linkService,
        private readonly SocialProfileSettingService $socialProfileSettingService,
    ) {
    }

    #[Route('/profile/u8k8s5b5n0i6d9a0', name: 'app_profile')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->isDeleted()) {
            $request->getSession()->invalidate(1);
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        $links = $this->linkService->getAllByUser($this->getUser());

        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        $profile = $this->profileService->getByUser($user);

        return $this->render('profile/index.html.twig', [
            'links' => $links,
            'email' => $this->getParameter('info_emal'),
            'socialProfileSetting' => $socialProfileSetting,
            'profile' => $profile
        ]);
    }
}
