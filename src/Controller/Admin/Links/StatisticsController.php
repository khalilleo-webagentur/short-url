<?php

declare(strict_types=1);

namespace App\Controller\Admin\Links;

use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/fY0qP9mL8pM5iD6s')]
class StatisticsController extends AbstractController
{
    use FormValidationTrait;

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkStatisticService $linkStatisticService
    ) {
    }

    #[Route('/links/statistics/home/{id}', name: 'app_admin_links_statistics_index')]
    public function index(?string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $statistics = [];

        $link = $this->linkService->getById($this->validateNumber($id));

        if ($link) {
            $statistics = $this->linkStatisticService->getAllByLink($link);
        }

        return $this->render('admin/links/statistics.html.twig', [
            'link' => $link,
            'statistics' => $statistics,
        ]);
    }
}
