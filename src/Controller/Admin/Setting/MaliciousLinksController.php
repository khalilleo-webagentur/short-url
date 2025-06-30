<?php

declare(strict_types=1);

namespace App\Controller\Admin\Setting;

use App\Helper\AppHelper;
use App\Service\MaliciousUrlsService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/settings/malicious-links/9b3vk2h1k4dfp3u0')]
class MaliciousLinksController extends AbstractController
{
    use FormValidationTrait;

    private const string APP_ADMIN_SETTINGS_INDEX = 'app_admin_settings_index';

    public function __construct(
        private readonly MaliciousUrlsService $maliciousUrlsService
    ) {
    }

    #[Route('/fix', name: 'app_admin_settings_extract_domain_from_url', methods: 'POST')]
    public function new(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

       $countUpdated = $this->maliciousUrlsService->updateDomains(AppHelper::MAX_LIMIT_TO_EXTRACT_DOMAINS_FROM_URLS);

        $this->addFlash('success', sprintf('Malicious [%s] domains from URLS.', $countUpdated));

        return $this->redirectToRoute(self::APP_ADMIN_SETTINGS_INDEX);
    }
}
