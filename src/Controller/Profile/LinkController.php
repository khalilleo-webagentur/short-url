<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Service\LinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LinkController extends AbstractController
{
    public function __construct(
        private readonly LinkService $linkService
    ) {
    }

    #[Route('/urls/zM1qM1kC4dB4aO8jS3xR5eO0zX2xB3oM', name: 'app_profile_my_urls')]
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
}
