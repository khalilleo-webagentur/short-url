<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FeaturesController extends AbstractController
{
    #[Route('/features/x9g7e3p5i1j6e1w6', name: 'app_features_index')]
    public function index(): Response
    {
        return $this->render('static/features.html.twig');
    }
}
