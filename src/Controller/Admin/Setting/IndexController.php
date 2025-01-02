<?php

declare(strict_types=1);

namespace App\Controller\Admin\Setting;

use App\Service\UserSettingService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/r7x8r2e9f4l1h1w5')]
class IndexController extends AbstractController
{
    use FormValidationTrait;

    public function __construct(
        private readonly UserSettingService $userSettingService
    ) {
    }

    #[Route('/y0uku8k1', name: 'app_admin_settings_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $settings = $this->userSettingService->getAll();

        return $this->render('admin/settings/index.html.twig', [
            'settings' => $settings
        ]);
    }

    #[Route('/edit/{id}', name: 'app_admin_setting_edit')]
    public function edit(?string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $settingId = $this->validateNumber($id);

        $setting = $this->userSettingService->getById($settingId);

        if (!$setting) {
            $this->addFlash('warning', 'Setting could not b found.');
            return $this->redirectToRoute('app_admin_settings_index');
        }

        return $this->render('admin/settings/edit.html.twig', [
            'setting' => $setting
        ]);
    }

    #[Route('/store/{id}', name: 'app_admin_setting_store', methods: ['POST'])]
    public function store(?string $id, Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $settingId = $this->validateNumber($id);

        $setting = $this->userSettingService->getById($settingId);

        if (!$setting) {
            $this->addFlash('warning', 'Setting could not b found.');
            return $this->redirectToRoute('app_admin_settings_index');
        }

        $allowDuplicatedUrls = $this->validateCheckbox($request->request->get('allowDuplicatedUrls'));
        $allowLinkAlias = $this->validateCheckbox($request->request->get('allowLinkAlias'));
        $allowRedirectAfterNewLink = $this->validateCheckbox($request->request->get('allowRedirectAfterNewLink'));
        $resetPrivateClicks = $this->validateCheckbox($request->request->get('resetPrivateClicks'));

        $this->userSettingService->save(
            $setting
                ->setAllowDuplicatedUrls($allowDuplicatedUrls)
                ->setAllowLinkAlias($allowLinkAlias)
                ->setAllowRedirectAfterNewLink($allowRedirectAfterNewLink)
                ->setResetPrivateClicks($resetPrivateClicks)
        );

        $this->addFlash('success', 'Changes has been saved.');

        return $this->redirectToRoute('app_admin_settings_index');
    }
}
