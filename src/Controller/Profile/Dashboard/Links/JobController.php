<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Links;

use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobController extends AbstractController
{
    use FormValidationTrait;

    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService          $linkService,
        private readonly LinkStatisticService $linkStatisticService,
    ) {
    }

    #[Route('/urls/star/h1j7jeg0d1g4q4m3/store/{id}', name: 'app_profile_job_star_store', methods: 'POST')]
    public function star(Request $request, ?string $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->validateCheckbox($request->request->get('star'))) {

            $id = $this->validateNumber($id);

            if ($link = $this->linkService->getById($id)) {
                $this->linkService->save($link->setIsFave(!$link->isFave()));
            }

            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $this->addFlash('warning', 'Link cannot be stared.');

        return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
    }

    #[Route('/urls/anonomyze/m1j4j6g0d1t4q4v0', name: 'app_profile_anonomyze', methods: 'POST')]
    public function anonomyze(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        foreach ($this->linkService->getAllByUser($this->getUser()) as $link) {

            foreach ($this->linkStatisticService->getAllByLink($link) as $row) {

                if ($row->getIpAddress() !== '_anonomyzed') {
                    $this->linkStatisticService->save($row->setIpAddress('_anonomyzed'));
                }
            }
        }

        $this->addFlash('notice', 'IPs has been anonomyzed in all statistics.');

        return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
    }
}
