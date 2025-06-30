<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Links;

use App\Service\LinkCollectionService;
use App\Service\LinkService;
use App\Traits\FormValidationTrait;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    use FormValidationTrait;

    private const string LINKS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService           $linkService,
        private readonly LinkCollectionService $linkCollectionService
    ) {
    }

    #[Route('/q/e3x4r2l5h6b0h2a2', name: 'app_search_urls', methods: 'POST')]
    public function search(Request $request): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $keyword = $this->validate($request->request->get('keyword'));

        if (!$keyword || strlen($keyword) < 2) {
            $this->addFlash('notice', 'Keyword length must be greater than or equal 2 Chars.');
            return $this->redirectToRoute(self::LINKS_DASHBOARD_ROUTE);
        }

        $links = $this->linkService->searchByUserAndTitle($user, $keyword);

        $collections = $this->linkCollectionService->getAllByUser($user);

        return $this->render('profile/dashboard/links/search.html.twig', [
            'keyword' => $keyword,
            'links' => $links,
            'collections' => $collections,
        ]);
    }

    #[Route('/f/e3x4r2l5h6b0h2a2', name: 'app_links_filter', methods: 'POST')]
    public function filter(Request $request): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        // $dateFrom = $this->validate($request->request->get('dateFrom'));
        // $timeFrom = $this->validate($request->request->get('timeFrom'));
        // $dateTo = $this->validate($request->request->get('dateTo'));
        // $timeTo = $this->validate($request->request->get('timeTo'));

        // if (!$dateFrom || !$timeFrom || $dateTo || $timeTo) {
        //     $this->addFlash('warning', 'Datetime filed is required.');
        //     return $this->redirectToRoute(self::LINKS_DASHBOARD_ROUTE);
        // }

        // $dateTimeFrom = DateTime::createFromFormat('Y-m-d H:i', $dateFrom . ' ' . $timeFrom);

        // if (false === $dateTimeFrom) {
        //     $this->addFlash('warning', 'Datetime from is not valid.');
        //     return $this->redirectToRoute(self::LINKS_DASHBOARD_ROUTE);
        // }

        // $dateTimeTo = DateTime::createFromFormat('Y-m-d H:i', $dateTo . ' ' . $timeTo);

        // if (false === $dateTimeFrom) {
        //     $this->addFlash('warning', 'Datetime from is not valid.');
        //     return $this->redirectToRoute(self::LINKS_DASHBOARD_ROUTE);
        // }

        $groupId = $this->validateNumber($request->request->get('group'));
        $isPublic = $this->validateCheckbox($request->request->get('isPublic'));
        $hasClicks = $this->validateCheckbox($request->request->get('clicks'));
        $isFave = $this->validateCheckbox($request->request->get('isFave'));

        $links = $this->linkService->filterByUser($user, $groupId, $isPublic, $hasClicks, $isFave);

        $collections = $this->linkCollectionService->getAllByUser($user);

        return $this->render('profile/dashboard/links/search.html.twig', [
            'keyword' => '',
            'links' => $links,
            'collections' => $collections,
        ]);
    }
}
