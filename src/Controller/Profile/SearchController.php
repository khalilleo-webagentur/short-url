<?php

declare(strict_types=1);

namespace App\Controller\Profile;

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

    #[Route('/q/tO0iP0kP0tS1gJ2q', name: 'app_search_urls', methods:'POST')]
    public function saerch(Request $request): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_USER');

        $keyword = $this->validate($request->request->get('keyword'));

        if (!$keyword || strlen($keyword) < 3) {
            $this->addFlash('notice', 'Keyword length must be greater than or equal 3 Chars.');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $links = $this->linkService->searchByUser($user, $keyword);

        return $this->render('profile/search.html.twig', [
            'keyword' => $keyword,
            'links' => $links,
        ]);
    }
}
