<?php

declare(strict_types=1);

namespace App\Controller\Admin\Links;

use App\Service\LinkService;
use App\Service\LinkStatisticService;
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

    private const ADMIN_LINKS_ROUTE = 'app_admin_links_index';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkStatisticService $linkStatisticService
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

    #[Route('/delete', name: 'app_admin_link_delete', methods: 'POST')]
    public function delete(Request $request): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $id = $this->validateNumber($request->request->get('linkId'));

        if ($link = $this->linkService->getById($id)) {

            $this->linkStatisticService->deleteAllByLink($link);

            $this->linkService->delete($link);

            $this->addFlash('success', 'Link and all accoiciated data (if any) has been deleted.');

            return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
        }

        $this->addFlash('warning', 'ID is undefined.');

        return $this->redirectToRoute(self::ADMIN_LINKS_ROUTE);
    }
}
