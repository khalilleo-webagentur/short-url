<?php

declare(strict_types=1);

namespace App\Controller\Admin\SocialProfile;

use App\Service\SocialProfileSettingService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/social-profile-settings/i7s4i3r8e7o2l4d5')]
class SettingsController extends AbstractController
{
    use FormValidationTrait;

    private const string ADMIN_SOCIAL_PROFILE_SETTINGS_ROUTE = 'app_admin_social_profile_settings_index';

    public function __construct(
        private readonly SocialProfileSettingService $socialProfileSettingService,
    ) {
    }

    #[Route('/home', name: 'app_admin_social_profile_settings_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $socialProfileSettings = $this->socialProfileSettingService->getAll();

        return $this->render('admin/social-profiles/settings.html.twig', [
            'socialProfileSettings' => $socialProfileSettings,
        ]);
    }
}
