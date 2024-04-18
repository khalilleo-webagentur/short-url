<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/url/statistic')]
class StatisticController extends AbstractController
{
    use FormValidationTrait;

    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';
    private const PROFILE_URLS_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkStatisticService $linkStatisticService
    ) {
    }

    #[Route('/b9u5z2w2j5r6u8m8/{id}', name: 'app_url_statistic_index')]
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

    #[Route('/delete/d1p8y1h6e2o1s7l4/{id}', name: 'app_url_statistic_delete', methods: 'POST')]
    public function delete(?string $id): RedirectResponse
    {
        $id = $this->validateNumber($id);

        if ($id > 0 && $link = $this->linkService->getByUserAndId($this->getUser(), $id)) {

            if ($statistic = $this->linkStatisticService->getOneByLink($link)) {

                $this->linkStatisticService->delete($statistic);

                $this->linkService->save(
                    $link->setCounter($link->getCounter() - 1)
                );

                $this->addFlash(
                    'success',
                    sprintf(
                        'Statistic for [%s] has been deleted.',
                        $link->getTitle() ?? $link->getToken()
                    )
                );

                return $this->redirectToRoute(self::PROFILE_URLS_ROUTE);
            }
        }

        $this->addFlash('warning', 'Data could not be found.');

        return $this->redirectToRoute(self::PROFILE_URLS_ROUTE);
    }
}
