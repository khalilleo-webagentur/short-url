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

#[Route('admin/dashboard/links/jobs/t0s3k2s1b4r4p1u5')]
class JobsController extends AbstractController
{
    use FormValidationTrait;

    private const string ADMIN_LINKS_ROUTE = 'app_admin_links_index';

    public function __construct(
        private readonly LinkService          $linkService,
        private readonly LinkStatisticService $linkStatisticService
    ) {
    }

    #[Route('/anonymize-statistics', name: 'app_admin_links_anonymize_statistics_store', methods: 'POST')]
    public function store(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $id = $this->validateNumber($request->request->get('linkId'));

        $link = $this->linkService->getById($id);

        $this->linkStatisticService->anonomize($link);

        $this->addFlash(
            'notice',
            sprintf(
                'Statistics for [%s] has been anonymized.',
                $link->getTitle() ?? $link->getToken()
            )
        );

        return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
    }
}
