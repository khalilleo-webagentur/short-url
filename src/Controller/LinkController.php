<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Link;
use App\Service\LinkService;
use App\Service\TokenGeneratorService;
use App\Traits\FormValidationTrait;
use App\Traits\RemoteTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LinkController extends AbstractController
{
    use FormValidationTrait;
    use RemoteTrait;

    private const HOME_ROUTE = 'app_home';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly TokenGeneratorService $tokenGeneratorService
    ) {
    }

    #[Route('/c/{token}', name: 'app_home_url')]
    public function url(?string $token): Response
    {
        if (!$this->linkService->getByToken($token)) {
            $this->addFlash('warning', 'Please type your URL - something like [google.com]');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        return $this->render('index.html.twig', [
            'token' => $token
        ]);
    }

    #[Route('/new', name: 'app_link_new', methods: 'POST')]
    public function new(Request $request): RedirectResponse
    {
        $url = $this->validateURL($request->request->get('url'));

        if (!$url) {
            $this->addFlash('warning', 'Please type your URL - something like [google.com]');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        $token = $this->tokenGeneratorService->randomToken();

        if ($this->linkService->getByToken($token)) {
            $token = $this->tokenGeneratorService->randomToken();
        }

        $userAgent = $request->server->get('HTTP_USER_AGENT');

        $user = $this->getUser();

        $model = new Link();

        $this->linkService->save(
            $model
                ->setUser($user)
                ->setUrl($url)
                ->setToken($token)
                ->setIpAdress($this->getRemot() ?: 'Local')
                ->setUserAgent($userAgent)
        );

        return $this->redirectToRoute('app_home_url', ['token' => $token]);
    }
}