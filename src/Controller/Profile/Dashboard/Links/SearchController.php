<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Links;

use App\Service\LinkService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    use FormValidationTrait;

    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService $linkService
    ) {
    }

    #[Route('/q/e3x4r2l5h6b0h2a2', name: 'app_search_urls', methods:'POST')]
    public function saerch(Request $request): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $keyword = $this->validate($request->request->get('keyword'));

        if (!$keyword || strlen($keyword) < 3) {
            $this->addFlash('notice', 'Keyword length must be greater than or equal 3 Chars.');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $links = $this->linkService->searchByUserAndTitle($user, $keyword);

        return $this->render('profile/dashboard/links/search.html.twig', [
            'keyword' => $keyword,
            'links' => $links,
            'keyword' => $keyword
        ]);
    }
}
