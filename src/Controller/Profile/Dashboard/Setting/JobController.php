<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Setting;

use App\Service\LinkCollectionService;
use App\Service\LinkService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/p/d/setting/job/k4k7j0gdd1t8q24b')]
class JobController extends AbstractController
{
    use FormValidationTrait;

    private const PROFILE_USER_SETTING_ROUTE = 'app_profile_dashboard_setting_index';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkCollectionService $linkCollectionService
    ) {
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
                $this->linkService->moveLinksWithoutAnyAssociationsToCollection($user, $collection);
                $this->addFlash('success', sprintf('Links has been moved to group [%s].', $collection->getName()));
                return $this->redirectToRoute(self::PROFILE_USER_SETTING_ROUTE);
            }

            $this->linkService->moveLinksToCollection($user, $collection);
            $this->addFlash('success', sprintf('Links with no Associations has been moved to group [%s].', $collection->getName()));
            return $this->redirectToRoute(self::PROFILE_USER_SETTING_ROUTE);
        }


        $this->addFlash('warning', 'Group could not be found.');

        return $this->redirectToRoute(self::PROFILE_USER_SETTING_ROUTE);
    }
}
