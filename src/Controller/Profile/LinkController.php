<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Entity\Link;
use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Service\TokenGeneratorService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/urls/zM1qM1kC4dB4aO8jS3xR5eO0zX2xB3oM')]
class LinkController extends AbstractController
{
    use FormValidationTrait;

    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkStatisticService $linkStatisticService,
        private readonly TokenGeneratorService $tokenGeneratorService
    ) {
    }

    #[Route('/home', name: 'app_profile_my_urls')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        $links = $this->linkService->getAllByUser($this->getUser());

        return $this->render('profile/links.html.twig', [
            'links' => $links
        ]);
    }

    #[Route('/new', name: 'app_profile_my_urls_new', methods: 'POST')]
    public function new(Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $link = $this->validateURL($request->request->get('iLink'));

        if (!$link) {
            $this->addFlash('warning', 'Link field is required.');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $token = $this->tokenGeneratorService->randomToken();

        if ($this->linkService->getByToken($token)) {
            $token = $this->tokenGeneratorService->randomToken();
        }

        $user = $this->getUser();

        $model = new Link();

        $title = $this->validate($request->request->get('iTitle'));

        $isPublic = $this->validateCheckbox($request->request->get('isPublic'));

        $this->linkService->save(
            $model
                ->setUser($user)
                ->setTitle($title)
                ->setUrl($link)
                ->setToken($token)
                ->setIsPublic($isPublic)
        );

        $this->addFlash('success', 'Link has been created.');

        return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
    }

    #[Route('/delete', name: 'app_profile_my_urls_delete', methods: 'POST')]
    public function delete(Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $id = $this->validateNumber($request->request->get('id'));

        if ($id <= 0) {
            $this->addFlash('warning', 'Unkown Id.');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $link = $this->linkService->getByUserAndId($user, $id);

        if (!$link) {
            $this->addFlash('warning', 'Unkown link');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $this->linkStatisticService->deleteAllByLink($link);

        $this->linkService->delete($link);

        $this->addFlash('success', 'Link has been deleted.');

        return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
    }
}
