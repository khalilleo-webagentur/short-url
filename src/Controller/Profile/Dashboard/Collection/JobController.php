<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Collection;

use App\Service\LinkCollectionService;
use App\Service\LinkService;
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
        private readonly LinkService $linkService,
        private readonly LinkCollectionService $linkCollectionService,
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

    #[Route('/move', name: 'app_profile_dashboard_setting_job_move', methods: 'POST')]
    public function move(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_auth');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        $groupId = $this->validateNumber($request->request->get('collection'));

        if ($collection = $this->linkCollectionService->getByUserAndId($user, $groupId)) {

            if ($this->validateCheckbox($request->request->get('moveOnly'))) {
                $count = $this->linkService->moveLinksWithoutAnyAssociationsToCollection($user, $collection);
                $this->addFlash('notice', sprintf('Links [%s] has been moved to group [%s].', $count, $collection->getName()));
                return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
            }

            $count = $this->linkService->moveLinksToCollection($user, $collection);
            $this->addFlash('notice', sprintf('Links with no Associations [%s] has been moved to group [%s].', $count, $collection->getName()));
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }


        $this->addFlash('warning', 'Group could not be found.');

        return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
    }
}
