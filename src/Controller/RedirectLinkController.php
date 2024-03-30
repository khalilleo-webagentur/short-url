<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\LinkService;
use App\Service\LinkStatisticService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RedirectLinkController extends AbstractController
{
    private const HOME_ROUTE = 'app_home';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkStatisticService $linkStatisticService
    ) {
    }

    #[Route('/{token}', name: 'app_redirect_url')]
    public function goto(?string $token): Response
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

        $this->linkService->save(
            $link
                ->setCounter($link->getCounter() + 1)
        );

        if ($link->isPublic()) {
            $this->linkStatisticService->create($link);
        }

        return $this->redirect($link->getUrl());
    }
}
