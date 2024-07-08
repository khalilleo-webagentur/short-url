<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\SocialProfile;

use App\Service\SocialProfileService;
use App\Service\SocialProfileSettingService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    use FormValidationTrait;

    private const SOCIAL_PROFILE_ROUTE = 'app_dashboard_social_profile_index';

    public function __construct(
        private readonly SocialProfileService $socialProfileService,
        private readonly SocialProfileSettingService $socialProfileSettingService
    ) {
    }

    #[Route('/{profile}', name: 'app_dashboard_social_profile_index')]
    public function index(?string $profile): Response
    {
        $socialProfileSetting = $this->socialProfileSettingService->getByName($this->validate($profile));

        if (!$socialProfileSetting) {
            return $this->redirectToRoute('app_home');
        }

        $profileLinks = $this->socialProfileService->getAll($socialProfileSetting->getUser());

        return $this->render('profile/dashboard/social-profile/index.html.twig', [
            'profileLinks' => $profileLinks,
            'socialProfileSetting' => $socialProfileSetting
        ]);
    }

    #[Route('/social-link/new', name: 'app_dashboard_social_profile_new', methods: 'POST')]
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        $route = ['profile' => $socialProfileSetting->getMainName()];

        $name = $this->validateAndReplaceSpace($request->request->get('iName'));

        if (!$name) {
            $this->addFlash('notice', 'Length must be greater than or equal 3 Chars.');
            return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
        }

        if ($this->socialProfileService->getByUserAndName($user, $name)) {
            $this->addFlash('notice', sprintf('Social link [%s] is already exists.', $name));
            return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
        }

        $this->socialProfileService->add($user, $name, 'youtube', 'red', 'https://youtube.x/khalilleo');

        $this->addFlash('success', sprintf('Social link [%s] has been added.', $name));

        return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
    }
}