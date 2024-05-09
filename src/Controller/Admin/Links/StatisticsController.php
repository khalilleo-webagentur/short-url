<?php

declare(strict_types=1);

namespace App\Controller\Admin\Links;

use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/a1o8b6i3e4d7a4e4')]
class StatisticsController extends AbstractController
{
    use FormValidationTrait;

    private const ADMIN_LINKS_ROUTE = 'app_admin_links_index';

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

    #[Route('/delete/{id}', name: 'app_admin_links_statistics_delete', methods: 'POST')]
    public function delete(?string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $link = $this->linkService->getById($this->validateNumber($id));

        if (!$link) {
            $this->addFlash('warning', 'Undefined Link ID.');
            return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
        }

        if ($linkStatistic = $this->linkStatisticService->getOneByLink($link)) {
            $this->linkService->save(
                $link->setCounter($link->getCounter() - 1)
            );
            $this->linkStatisticService->delete($linkStatistic);
            $this->addFlash('success', 'Statistic has been deleted.');
            return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
        }

        $this->addFlash('warning', 'Undefined Statistic ID.');

        return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
    }
}
