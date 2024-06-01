<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Links;

use App\Helper\AppHelper;
use App\Service\Export\UserLinksExport;
use App\Service\LinkService;
use App\Traits\FormValidationTrait;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile/links/export/k1o4j7g0s4t4q9m1')]
class ExportController extends AbstractController
{
    use FormValidationTrait;

    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService $linkService,
    ) {
    }

    #[Route('/export', name: 'app_profile_links_export', methods: 'POST')]
    public function export(Request $request, UserLinksExport $userLinksExport): RedirectResponse|Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        $exportAsOption = $this->validate($request->request->get('exportAs'));

        if (!in_array($exportAsOption, AppHelper::AVAILABLE_LINKS_EXPORT_OPTIONS, true)) {
            $exportAsOption = AppHelper::DEFAULT_LINKS_OPTION_EXPORT_AS_JSON;
        }

        $data = '';

        if ($exportAsOption === 'json') {
            try {
                $data = $userLinksExport->asJson($user);
            } catch (Exception $e) {
                $data = '';
            }
        }

        if ($data === '') {
            $this->addFlash('waning', 'Data could not be exported.');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        return new Response($data);
    }
}
