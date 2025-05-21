<?php

declare(strict_types=1);

namespace App\Controller\Admin\MaliciousUrl;

use App\Entity\MaliciousUrl;
use App\Service\MaliciousUrlsService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('dashboard/malicious-links/search/7b6vk2s1k474p3u0')]
class SearchController extends AbstractController
{
    use FormValidationTrait;
    private const ADMIN_MALICIOUS_LINKS_ROUTE = 'app_admin_links_malicious_index';

    public function __construct(
        private readonly MaliciousUrlsService $maliciousUrlsService
    ) {
    }

    #[Route('/q', name: 'app_admin_links_malicious_search', methods: 'POST')]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $keyword = $this->validate($request->request->get('keyword'));

        if (empty($keyword)) {
            $this->addFlash('warning', 'Field is required.');
            return $this->redirectToRoute(self::ADMIN_MALICIOUS_LINKS_ROUTE);
        }

        $maliciousUrls = $this->maliciousUrlsService->search($keyword);

        return $this->render('admin/malicious-urls/search.html.twig', [
            'keyword' => $keyword,
            'maliciousUrls' => $maliciousUrls,
            'maliciousUrlsCount' => count($maliciousUrls)
        ]);
    }
}
