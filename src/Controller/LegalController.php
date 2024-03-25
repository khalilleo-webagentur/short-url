<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    #[Route('/imprint', name: 'app_legal_imprint')]
    public function impressum(): Response
    {
        return $this->render('static/imprint.html.twig');
    }

    #[Route('/privacy-policy', name: 'app_legal_privacy_policy')]
    public function datenschutz(): Response
    {
        return $this->render('static/privacy-policy.html.twig');
    }
}
