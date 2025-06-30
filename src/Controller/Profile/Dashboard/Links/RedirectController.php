<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Links;

use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Service\MaliciousUrlsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RedirectController extends AbstractController
{
    private const string HOME_ROUTE = 'app_home';

    public function __construct(
        private readonly LinkService          $linkService,
        private readonly LinkStatisticService $linkStatisticService,
        private readonly MaliciousUrlsService $maliciousUrlsService,
    ) {
    }

    #[Route('/{token}', name: 'app_redirect_url')]
    public function goto(?string $token): Response|RedirectResponse
    {
        if (!$token) {
            $this->addFlash('notice', 'URL cannot be found.');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        $link = $this->linkService->getByToken($token);

        if (!$link) {
            $this->addFlash('notice', 'URL cannot be found.');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        if ($this->maliciousUrlsService->isMaliciousUrl($link->getUrl())) {
            return $this->render('static/malicious-link.html.twig', [
                'link' => $link,
            ]);
        }

        if ($link->isPublic()) {
            $this->linkService->save($link->setCounter($link->getCounter() + 1));
            $this->linkStatisticService->create($link);
        }

        return $this->redirect($link->getUrl());
    }
}
