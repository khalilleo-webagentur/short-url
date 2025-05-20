<?php

declare(strict_types=1);

namespace App\Controller;

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

class VerifySourceLinkController extends AbstractController
{
    use FormValidationTrait;
    use RemoteTrait;

    private const HOME_ROUTE = 'app_home';
    private const VERIFY_SOURCE_LINK_ROUTE = 'app_verify_source_shorten_link_index';

    public function __construct(
        private readonly LinkService           $linkService,
        private readonly TokenGeneratorService $tokenGeneratorService,
        private readonly UserSettingService    $userSettingService,
        private readonly MaliciousUrlsService  $maliciousUrlsService,
        private readonly MonologService        $monolog
    ) {
    }

    #[Route('verify-source/shorten-link', name: 'app_verify_source_shorten_link_index')]
    public function index(): Response
    {
        return $this->render('static/verify-source-link-form.html.twig');
    }

    #[Route('/verify-source/shorten-link-check/{hash?}', name: 'app_verify_source_shorten_link_check', methods: 'POST')]
    public function check(Request $request): RedirectResponse|Response
    {
        $url = $this->validateURL($request->request->get('url'));

        if (!$url) {
            $this->addFlash('warning', 'Please paste your URL or type it.');
            return $this->redirectToRoute(self::VERIFY_SOURCE_LINK_ROUTE);
        }

        $this->monolog->logger->debug(sprintf('Verify source link %s | IP: %s', $url, $this->getRemote()));

        $token = $this->extractPathFromUrl($url);

        if (!$token) {
            $this->addFlash('warning', 'Your URL is not valid.');
            return $this->redirectToRoute(self::VERIFY_SOURCE_LINK_ROUTE);
        }

        $link = $this->linkService->getByToken(str_replace('/', '', $token));

        if (!$link) {
            $this->addFlash('warning', 'Not fount.');
            return $this->redirectToRoute(self::VERIFY_SOURCE_LINK_ROUTE);
        }

        return $this->render('static/verify-source-link.html.twig', [
            'link' => $link,
        ]);
    }
}
