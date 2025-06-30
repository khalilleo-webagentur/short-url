<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\SocialProfile;

use App\Service\SocialProfileService;
use App\Service\SocialProfileSettingService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile/dashboard/profile-settings')]
class SettingsController extends AbstractController
{
    use FormValidationTrait;

    private const string SOCIAL_PROFILE_ROUTE = 'app_dashboard_social_profile_index';
    private const string SOCIAL_PROFILE_SETTINGS_ROUTE = 'app_dashboard_social_profile_settings_index';

    public function __construct(
        private readonly SocialProfileService        $socialProfileService,
        private readonly SocialProfileSettingService $socialProfileSettingService
    ) {
    }

    #[Route('/home', name: 'app_dashboard_social_profile_settings_index')]
    public function index(): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        return $this->render('profile/dashboard/social-profile/settings.html.twig', [
            'socialProfileSetting' => $socialProfileSetting
        ]);
    }

    #[Route('/social-profile-name/store', name: 'app_dashboard_social_profile_setting_store_alias', methods: 'POST')]
    public function aliasStore(Request $request): RedirectResponse
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        $route = ['profile' => $socialProfileSetting->getMainName()];

        $mainName = $this->validate($request->request->get('iAlias'));

        if (!$mainName) {
            $this->addFlash('notice', 'Alias is required.');
            return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
        }

        if ($mainName === $socialProfileSetting->getMainName()) {
            $this->addFlash('notice', sprintf('Nothing has been changed. Your profile alias name is still [%s].', $mainName));
            return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
        }

        if ($this->socialProfileSettingService->getByName($mainName)) {
            $this->addFlash('notice', 'Alias is reserved.');
            return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
        }

        $mainName = preg_replace('/[^a-zA-Z0-9]/', '_', $mainName);

        $this->socialProfileSettingService->save(
            $socialProfileSetting
                ->setMainName($mainName)
        );

        $route = ['profile' => $socialProfileSetting->getMainName()];

        $this->addFlash('success', sprintf('Profile Name [%s] has been saved.', $mainName));

        return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
    }


    #[Route('/description/store', name: 'app_dashboard_social_profile_setting_store_desc', methods: 'POST')]
    public function storeDescription(Request $request): RedirectResponse
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        $route = ['profile' => $socialProfileSetting->getMainName()];

        $about = $this->validateTextarea($request->request->get('iDesc'));

        $this->socialProfileSettingService->save(
            $socialProfileSetting
                ->setDescription($about)
        );

        $this->addFlash('success', 'About has been updated.');

        return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
    }

    #[Route('/is-profile-public/store', name: 'app_dashboard_social_profile_setting_store_is_public', methods: 'POST')]
    public function storeIsProfilePublic(Request $request): RedirectResponse
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        if ($this->validateCheckbox($request->request->get('publicSetting'))) {
            $isPublic = $this->validateCheckbox($request->request->get('isPublic'));
            $this->socialProfileSettingService->save($socialProfileSetting->setPublic(!$isPublic));
        }

        $this->addFlash('success', 'Change has been saved.');

        return $this->redirectToRoute(self::SOCIAL_PROFILE_SETTINGS_ROUTE);
    }
}
