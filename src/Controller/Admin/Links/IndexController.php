<?php

declare(strict_types=1);

namespace App\Controller\Admin\Links;

use App\Service\LinkCollectionService;
use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/z9l7a1k6f6d9y6c2/links')]
class IndexController extends AbstractController
{
    use FormValidationTrait;

    private const string ADMIN_LINKS_ROUTE = 'app_admin_links_index';

    public function __construct(
        private readonly LinkService           $linkService,
        private readonly LinkStatisticService  $linkStatisticService,
        private readonly UserService           $userService,
        private readonly LinkCollectionService $linkCollectionService
    ) {
    }

    #[Route('/home', name: 'app_admin_links_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $links = $this->linkService->getAll();

        return $this->render('admin/links/index.html.twig', [
            'links' => $links,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_admin_link_edit')]
    public function edit(?string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $link = $this->linkService->getById($this->validateNumber($id));

        if (!$link) {
            $this->addFlash('warning', 'Link could not be found!');
            return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
        }

        $users = $this->userService->getAll();

        $collections = $this->linkCollectionService->getAllByUser($link->getUser());

        return $this->render('admin/links/edit.html.twig', [
            'link' => $link,
            'users' => $users,
            'collections' => $collections
        ]);
    }

    #[Route('/store/{id}', name: 'app_admin_link_store', methods: 'POST')]
    public function store(?string $id, Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $title = $this->validate($request->request->get('title'));
        $url = $this->validate($request->request->get('url'));
        $token = $this->validate($request->request->get('token'));
        $counter = max($this->validate($request->request->get('counter')), 0);

        if (!$url || !$token) {
            $this->addFlash('warning', 'URL and short token are required.');
            return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
        }

        $link = $this->linkService->getById($this->validateNumber($id));

        if (!$link) {
            $this->addFlash('warning', 'Link could not be found!');
            return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
        }

        $targetUser = $this->userService->getById(
            $this->validateNumber($request->request->get('uId'))
        );

        $groupId = $this->validateNumber($request->request->get('group'));

        $group = $this->linkCollectionService->getByUserAndId($targetUser, $groupId);

        $isPublic = $this->validateCheckbox($request->request->get('isPublic'));

        $this->linkService->save(
            $link
                ->setUser($targetUser)
                ->setCollection($group)
                ->setTitle($title)
                ->setUrl($this->replaceAmpersand($url))
                ->setToken($token)
                ->setIsPublic($isPublic)
                ->setCounter((int)$counter)
        );

        $this->addFlash('success', 'Changes has been saved.');

        return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
    }

    #[Route('/delete', name: 'app_admin_link_delete', methods: 'POST')]
    public function delete(Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $id = $this->validateNumber($request->request->get('linkId'));

        if ($link = $this->linkService->getById($id)) {

            $count = $this->linkStatisticService->deleteAllByLink($link);

            $this->linkService->delete($link);

            $count > 0
                ? $this->addFlash('success', sprintf('Link and all associated statistics [%s] has been deleted.', $count))
                : $this->addFlash('notice', 'Link has been deleted.');

            return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
        }

        $this->addFlash('warning', 'ID is undefined.');

        return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
    }

}
