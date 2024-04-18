<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Service\LinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly LinkService $linkService
    ) {
    }

    #[Route('/profile/u8k8s5b5n0i6d9a0', name: 'app_profile')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        $links = $this->linkService->getAllByUser($this->getUser());

        return $this->render('profile/index.html.twig', [
            'links' => $links,
            'email' => $this->getParameter('info_emal'),
        ]);
    }
}
