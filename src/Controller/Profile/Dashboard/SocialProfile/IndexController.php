<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\SocialProfile;

use App\Service\ProfileService;
use App\Service\SocialProfileService;
use App\Service\SocialProfileSettingService;
use App\Service\SocialProfileStatisticsService;
use App\Service\SocialProfileVisitorService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    use FormValidationTrait;

    private const SOCIAL_PROFILE_ROUTE = 'app_dashboard_social_profile_index';
    private const HOME_ROUTE = 'app_home';

    public function __construct(
        private readonly ProfileService $profileService,
        private readonly SocialProfileService $socialProfileService,
        private readonly SocialProfileSettingService $socialProfileSettingService,
        private readonly SocialProfileVisitorService $socialProfileVisitorService,
        private readonly SocialProfileStatisticsService $socialProfileStatisticsService,
    ) {
    }

    #[Route('/u/{profile}', name: 'app_dashboard_social_profile_index')]
    public function index(?string $profile): Response
    {
        $socialProfileSetting = $this->socialProfileSettingService->getByName($this->validate($profile));

        if (!$socialProfileSetting) {
            return $this->redirectToRoute('app_home');
        }

        $user = $socialProfileSetting->getUser();

        $profileLinks = $this->socialProfileService->getAllByUser($user);

        $this->socialProfileVisitorService->add($user, $socialProfileSetting);

        $profile = $this->profileService->getByUser($user);

        return $this->render('profile/dashboard/social-profile/index.html.twig', [
            'profileLinks' => $profileLinks,
            'socialProfileSetting' => $socialProfileSetting,
            'profile' => $profile
        ]);
    }

    #[Route('/social-profile/u7m8s6r1/{nums}/{id}/{mainName}', name: 'app_dashboard_social_profile_redirect_to')]
    public function view(?string $id, ?string $mainName): RedirectResponse
    {
        $profileOwnerMainName = $this->validate($mainName); 

        $socialProfileSetting = $this->socialProfileSettingService->getByName($profileOwnerMainName);

        if (!$socialProfileSetting) {
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        $profileOwner = $socialProfileSetting->getUser();

        $socialProfile = $this->socialProfileService->getByUserAndId(
            $profileOwner,
            $this->validateNumber($id)
        );

        if (!$socialProfile) {
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        $this->socialProfileStatisticsService->create($profileOwner, $socialProfile);

       return $this->redirect($socialProfile->getUrl());
    }

    #[Route('/social-link/u0u8s9r4/edit/{id}', name: 'app_dashboard_social_profile_edit')]
    public function edit(?string $id): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $socialProfile = $this->socialProfileService->getByUserAndId(
            $user,
            $this->validateNumber($id)
        );

        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        if (!$socialProfile) {
            return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, ['profile' => $socialProfileSetting->getMainName()]);
        }

        return $this->render('profile/dashboard/social-profile/edit.html.twig', [
            'socialProfile' => $socialProfile,
            'socialProfileSetting' => $socialProfileSetting
        ]);
    }

    #[Route('/social-link/o0o1i7d8/store/{id}', name: 'app_dashboard_social_profile_store', methods: 'POST')]
    public function store(?string $id, Request $request): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $socialProfile = $this->socialProfileService->getByUserAndId(
            $user,
            $this->validateNumber($id)
        );

        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        $route = ['profile' => $socialProfileSetting->getMainName()];

        if (!$socialProfile) {
            $this->addFlash('warning', sprintf('Unknown Link ID [%s]', $id));
            return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
        }

        if ($this->validateCheckbox($request->request->get('delete'))) {
            $this->socialProfileService->delete($socialProfile);
            $this->addFlash('success', 'Social link has been deleted.');
            return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
        }

        $platform = $this->validate($request->request->get('iName'));

        $username = $this->validate($request->request->get('iUsername'));

        $profileUrl = $this->validateURL($request->request->get('iURL'));

        if (!$platform || !$username || !$profileUrl) {
            $this->addFlash('notice', 'All fields are required.');
            return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
        }

        $this->socialProfileService->save(
            $socialProfile
                ->setPlatform($platform)
                ->setUsername($username)
                ->setUrl($profileUrl)
        );

        $this->addFlash('success', sprintf('Social link [%s] has been updated.', $username));

        return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
    }

    #[Route('/social-link/o0o1i7d8/new', name: 'app_dashboard_social_profile_new', methods: 'POST')]
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

        $route = ['profile' => $socialProfileSetting->getMainName()];

        $platform = $this->validate($request->request->get('iSource'));

        $username = $this->validate($request->request->get('iName'));

        $profileUrl = $this->validateURL($request->request->get('iUrl'));

        if (!$platform || !$username || !$profileUrl) {
            $this->addFlash('notice', 'All fields are required.');
            return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
        }

        $this->socialProfileService->add($user, $platform, $username, $profileUrl);

        $this->addFlash('success', sprintf('Social link [%s] has been added.', $username));

        return $this->redirectToRoute(self::SOCIAL_PROFILE_ROUTE, $route);
    }
}
