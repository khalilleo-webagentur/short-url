<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\LinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RedirectLinkController extends AbstractController
{
    public function __construct(
        private readonly LinkService $linkService
    ) {
    }

    #[Route('/{token}', name: 'app_redirect_url')]
    public function goto(?string $token): Response
    {
        if (!$token) {
            $this->addFlash('notice', 'URL cannot be found.');
            return $this->redirectToRoute('app_home');
        }

        $link = $this->linkService->getByToken($token);

        if (!$link) {
            $this->addFlash('notice', 'URL cannot be found.');
            return $this->redirectToRoute('app_home');
        }

        $this->linkService->save(
            $link
                ->setCounter($link->getCounter() + 1)
        );

        return $this->redirect($link->getUrl());
    }
}
