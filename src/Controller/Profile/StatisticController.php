<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatisticController extends AbstractController
{
    use FormValidationTrait;

    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkStatisticService $linkStatisticService
    ) {
    }

    #[Route('/url/statistic/pP8jK8qF3tU8iT6m/{id}', name: 'app_url_statistic_index')]
    public function index(?string $id): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $link = $this->linkService->getByUserAndId($user, $this->validateNumber($id));

        if (!$link) {
            $this->addFlash('notice', 'URL could not be found.');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $statistics = $this->linkStatisticService->getAllByLink($link);

        return $this->render('profile/statistic.html.twig', [
            'link' => $link,
            'statistics' => $statistics,
        ]);
    }
}
