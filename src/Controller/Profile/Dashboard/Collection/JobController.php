<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Collection;

use App\Service\LinkCollectionService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard/collection/job/d4x9l2l5h6b0h2a8')]
class JobController extends AbstractController
{
    use FormValidationTrait;

    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkCollectionService $linkCollectionService
    ) {
    }

    #[Route('/store-default-collection', name: 'app_links_collection_job_store_collection', methods: 'POST')]
    public function defaultCollection(Request $request): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $id = $this->validateNumber($request->request->get('group'));

        if ($id === 0) {
            $this->linkCollectionService->resetAll($user);
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        if ($collection = $this->linkCollectionService->getByUserAndId($user, $id)) {
            $this->linkCollectionService->resetAndUpdateDefault($user, $collection);
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $this->addFlash('warning', 'Group could not be found.');

        return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
    }
}
