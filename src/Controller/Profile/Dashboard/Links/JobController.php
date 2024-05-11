<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Links;

use App\Service\LinkService;
use App\Service\LinkStatisticService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobController extends AbstractController
{
    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkStatisticService $linkStatisticService,
    ) {
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
