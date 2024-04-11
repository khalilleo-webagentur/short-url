<?php

declare(strict_types=1);

namespace App\Controller\Admin\Links;

use App\Service\LinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/rD9zA8lC3kU9tA5f')]
class IndexController extends AbstractController
{
    public function __construct(
        private readonly LinkService $linkService
    ) {
    }

    #[Route('/links/home', name: 'app_admin_links_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $links = $this->linkService->getAll();

        return $this->render('admin/links/index.html.twig', [
            'links' => $links,
        ]);
    }
}
