<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Links;

use App\Entity\Link;
use App\Helper\AppHelper;
use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Service\TokenGeneratorService;
use App\Service\UserSettingService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/urls/c3k1f2i0i0n7r7f4')]
class IndexController extends AbstractController
{
    use FormValidationTrait;

    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkStatisticService $linkStatisticService,
        private readonly TokenGeneratorService $tokenGeneratorService,
        private readonly UserSettingService $userSettingService
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

        return $this->render('profile/dashboard/links/index.html.twig', [
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

        $user = $this->getUser();

        if ($this->linkService->getOneByUserAndUrl($user, $link)) {

            $allowDuplicatedUrls = $this->userSettingService->allowDuplicatedUrls($user);

            if (!$allowDuplicatedUrls) {
                $this->addFlash('notice', 'You have to update your config [allowDuplicatedUrls] in setting.');
                return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
            }
        }

        $token = $this->tokenGeneratorService->randomTokenForLink();

        if ($this->linkService->getByToken($token)) {
            $token = $this->tokenGeneratorService->randomTokenForLink();
        }

        $title = $this->validate($request->request->get('iTitle'));

        $isPublic = $this->validateCheckbox($request->request->get('isPublic'));

        $model = new Link();

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

    #[Route('/eidt/{id}', name: 'app_profile_my_urls_edit')]
    public function edit(?string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $link = $this->linkService->getByUserAndId($user, $this->validateNumber($id));

        if (!$link) {
            $this->addFlash('warning', 'Unkown link');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $allowLinkAlias = $this->userSettingService->allowLinkAlias($user);

        return $this->render('profile/dashboard/links/edit.html.twig', [
            'link' => $link,
            'allowLinkAlias' => $allowLinkAlias
        ]);
    }

    #[Route('/store/{id}', name: 'app_profile_my_urls_store', methods: 'POST')]
    public function store(?string $id, Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $link = $this->linkService->getByUserAndId($user, $this->validateNumber($id));

        if (!$link) {
            $this->addFlash('warning', 'Unkown link');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        $url = $this->validateURL($request->request->get('iUrl'));

        if (!$url) {
            $this->addFlash('warning', 'Long link field is required.');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        if ($this->linkService->getOneByUserAndUrl($user, $url) && $url !== $link->getUrl()) {

            $allowDuplicatedUrls = $this->userSettingService->allowDuplicatedUrls($user);

            if (!$allowDuplicatedUrls) {
                $this->addFlash('notice', 'You have to update your config [allowDuplicatedUrls] in setting.');
                return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
            }
        }

        $token = $this->validate($request->request->get('iCode'));

        $aliasLength = AppHelper::DEFAULT_ALIAS_LENGTH;

        if (strlen($token) < $aliasLength) {
            $this->addFlash('warning', sprintf('Alias length must be geraeter or equal [8] chars.', $aliasLength));
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        if ($token !== $link->getToken()) {

            if ($token && $this->linkService->getByToken($token)) {
                $this->addFlash('warning', 'Token is not valid. Please try another one!');
                return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
            }
        }

        if (!$token) {
            $token = $link->getToken();
        }

        $title = $this->validate($request->request->get('iTitle'));

        $isPublic = $this->validateCheckbox($request->request->get('isPublic'));
        $isFave = $this->validateCheckbox($request->request->get('isFave'));

        $this->linkService->save(
            $link
                ->setTitle($title)
                ->setUrl($url)
                ->setToken($this->replaceSpecialChars($token))
                ->setIsPublic($isPublic)
                ->setIsFave($isFave)
        );

        $this->addFlash('success', 'Link has been updated.');

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

        $this->addFlash('success', 'Link and all associated statistics if any has been deleted.');

        return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
    }
}
