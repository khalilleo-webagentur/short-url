<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Link;
use App\Service\LinkService;
use App\Service\MaliciousUrlsService;
use App\Service\MonologService;
use App\Service\TokenGeneratorService;
use App\Service\UserSettingService;
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
        private readonly LinkService           $linkService,
        private readonly TokenGeneratorService $tokenGeneratorService,
        private readonly UserSettingService    $userSettingService,
        private readonly MaliciousUrlsService  $maliciousUrlsService,
        private readonly MonologService        $monolog
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
            $this->addFlash('warning', 'Please paste your URL or type it.');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        if ($this->maliciousUrlsService->isMaliciousUrl($url)) {
            $this->addFlash('warning', 'This URL is on Blacklist.');
            return $this->redirectToRoute(self::HOME_ROUTE);
        }

        $token = $this->tokenGeneratorService->randomTokenForLink();

        if ($this->linkService->getByToken($token)) {
            $token = $this->tokenGeneratorService->randomTokenForLink();
        }

        $user = $this->getUser();

        if ($user && $this->linkService->getOneByUserAndUrl($user, $url)) {

            $allowDuplicatedUrls = $this->userSettingService->allowDuplicatedUrls($user);

            if (!$allowDuplicatedUrls) {
                $message = 'You have to update your config [allowDuplicatedUrls] in setting - since the long link [%s] already exists.';
                $this->addFlash('notice', sprintf($message, $url));
                return $this->redirectToRoute(self::HOME_ROUTE);
            }
        }

        $model = new Link();

        $this->linkService->save(
            $model
                ->setUser($user)
                ->setUrl($this->replaceAmpersand($url))
                ->setToken($token)
        );

        return $this->redirectToRoute('app_home_url', ['token' => $token]);
    }
}
