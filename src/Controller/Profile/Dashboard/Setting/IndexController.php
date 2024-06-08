<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Setting;

use App\Service\UserSettingService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    use FormValidationTrait;

    private const PROFILE_USER_SETTING_ROUTE = 'app_profile_dashboard_setting_index';

    public function __construct(
        private readonly UserSettingService $userSettingService
    ) {
    }

    #[Route('/urls/setting/p1k4j8g7d1t8q4vk', name: 'app_profile_dashboard_setting_index')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_auth');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        $userSetting = $this->userSettingService->getOneByUser($this->getUser());

        return $this->render('profile/dashboard/setting/index.html.twig', [
            'userSetting' => $userSetting
        ]);
    }

    #[Route('/store/{id}', name: 'app_profile_dashboard_setting_store', methods: 'POST')]
    public function store(?string $id, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_auth');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $userSetting = $this->userSettingService->getOneByUser($user);

        if (!$userSetting) {
            $this->addFlash('warning', 'Config could not be updated.');
            return $this->redirectToRoute(self::PROFILE_USER_SETTING_ROUTE);
        }

        if ($this->validateCheckbox($request->request->get('allowDuplicatedUrlsForm'))) {
            $isActive = !$this->validateCheckbox($request->request->get('allowDuplicatedUrls'));
            $this->userSettingService->save($userSetting->setAllowDuplicatedUrls($isActive));
        }

        if ($this->validateCheckbox($request->request->get('allowLinkAliasForm'))) {
            $isActive = !$this->validateCheckbox($request->request->get('allowLinkAlias'));
            $this->userSettingService->save($userSetting->setAllowLinkAlias($isActive));
        }

        if ($this->validateCheckbox($request->request->get('allowRedirectAfterNewLinkForm'))) {
            $isActive = !$this->validateCheckbox($request->request->get('allowRedirectAfterNewLink'));
            $this->userSettingService->save($userSetting->setAllowRedirectAfterNewLink($isActive));
        }

        $this->addFlash('success', 'Config has been updated.');

        return $this->redirectToRoute(self::PROFILE_USER_SETTING_ROUTE);
    }
}
