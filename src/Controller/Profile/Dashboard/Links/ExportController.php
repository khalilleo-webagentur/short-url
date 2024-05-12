<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Links;

use App\Service\LinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile/links/export/k1o4j7g0s4t4q9m1')]
class ExportController extends AbstractController
{
    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService $linkService,
    ) {
    }

    #[Route('/export', name: 'app_profile_links_export', methods: 'POST')]
    public function export(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        // @todo

        $this->addFlash('notice', 'Not implemented yet!');

        return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
    }
}
