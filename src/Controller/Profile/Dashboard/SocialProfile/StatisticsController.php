<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\SocialProfile;

use App\Service\SocialProfileService;
use App\Service\SocialProfileSettingService;
use App\Service\SocialProfileStatisticsService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/social-profile/statistics')]
class StatisticsController extends AbstractController
{
    use FormValidationTrait;

    private const string SOCIAL_PROFILE_STATISTICS_ROUTE = 'app_dashboard_social_profile_statistics_setting_index';
    private const string SOCIAL_PROFILE_ROUTE = 'app_dashboard_social_profile_index';

    public function __construct(
        private readonly SocialProfileService           $socialProfileService,
        private readonly SocialProfileStatisticsService $socialProfileStatisticsService,
        private readonly SocialProfileSettingService    $socialProfileSettingService,
    ) {
    }

    #[Route('/view/{id}', name: 'app_dashboard_social_profile_statistics_setting_index')]
    public function index(?string $id): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        $platformAndUsername = '';
        $socialProfileStatistics = [];

        if ($socialProfile = $this->socialProfileService->getByUserAndId($user, $this->validateNumber($id))) {
            $socialProfileStatistics = $this->socialProfileStatisticsService->getAllBySocialProfileAndUser($socialProfile, $user);
            $platformAndUsername = ': ' . $socialProfile->getUsername() . ' (' . $socialProfile->getPlatform() . ')';
            $this->socialProfileStatisticsService->markAllAsSeen($user, $socialProfile);
        }

        return $this->render('profile/dashboard/social-profile/statistics.html.twig', [
            'socialProfileStatistics' => $socialProfileStatistics,
            'socialProfileSetting' => $socialProfileSetting,
            'platformAndUsername' => $platformAndUsername
        ]);
    }

    #[Route('/delete/{id}', name: 'app_dashboard_social_profile_statistics_delete', methods: 'POST')]
    public function delete(?string $id): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $id = $this->validateNumber($id);

        $socialProfileStatistics = $this->socialProfileStatisticsService->getByUserAndId($user, $id);

        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        if (!$socialProfileStatistics || !$socialProfileSetting) {
            $this->addFlash('warning', 'ID is not defined.');
            return $this->redirectToRoute(self::SOCIAL_PROFILE_STATISTICS_ROUTE, ['id' => $id]);
        }

        $this->socialProfileStatisticsService->delete($socialProfileStatistics);

        $this->addFlash('success', 'Social profile statistics has been deleted.');

        return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, ['profile' => $socialProfileSetting->getMainName()]);
    }
}
