<?php

declare(strict_types=1);

namespace App\Controller\Admin\Links;

use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/links/jobs/gT6sE7gU4eO7yQ1v')]
class JobsController extends AbstractController
{
    use FormValidationTrait;

    private const ADMIN_LINKS_ROUTE = 'app_admin_links_index';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkStatisticService $linkStatisticService
    ) {
    }

    #[Route('/anonomize-statistics', name: 'app_admin_links_anonomize_statistics_store', methods: 'POST')]
    public function store(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $id = $this->validateNumber($request->request->get('linkId'));

        if ($id > 0 && $link = $this->linkService->getById($id)) {
            $this->linkStatisticService->anonomize($link);
            $this->addFlash('success', sprintf('All statistics for [%s] has been anonomized.', $link->getTitle() ?? $link->getToken()));
            return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
        }

        $this->addFlash('notice', 'No data has been found.');

        return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
    }
}
