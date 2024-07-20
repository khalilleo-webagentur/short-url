<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\SocialProfile;

use App\Service\SocialProfileService;
use App\Service\SocialProfileSettingService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SocialProfileSettingController extends AbstractController
{
    use FormValidationTrait;

    private const SOCIAL_PROFILE_ROUTE = 'app_dashboard_social_profile_index';

    public function __construct(
        private readonly SocialProfileService $socialProfileService,
        private readonly SocialProfileSettingService $socialProfileSettingService
    ) {
    }

    #[Route('/social-profile-setting/alias/store', name: 'app_dashboard_social_profile_setting_store_alias', methods: 'POST')]
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

        $mainName = preg_replace('/[^a-zA-Z0-9_.]/', '_', $mainName);

        $this->socialProfileSettingService->save(
            $socialProfileSetting
                ->setMainName($mainName)
        );

        $this->addFlash('success', sprintf('Alias [%s] has been saved.', $mainName));

        return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
    }


    #[Route('/social-profile-setting/description/store', name: 'app_dashboard_social_profile_setting_store_desc', methods: 'POST')]
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
}
