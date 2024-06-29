<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Collection;

use App\Service\LinkCollectionService;
use App\Service\LinkService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard/collections/9kx9l2l5h6b0h2v4')]
class IndexController extends AbstractController
{
    use FormValidationTrait;

    private const URLS_DASHBOARD_ROUTE = 'app_profile_my_urls';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkCollectionService $linkCollectionService
    ) {
    }

    #[Route('/home', name: 'app_link_collection_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $collections = $this->linkCollectionService->getAllByUser($user);

        if (!$collections) {
            $this->addFlash('warning', 'No group has been found.');
            return $this->redirectToRoute(self::URLS_DASHBOARD_ROUTE);
        }

        return $this->render('profile/dashboard/collections/index.html.twig', [
            'collections' => $collections
        ]);
    }
}