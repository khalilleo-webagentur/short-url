<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PricingController extends AbstractController
{
    #[Route('/pricing/lQ0jU2fQ0wD6mI2j', name: 'app_pricing_index')]
    public function index(): Response
    {
        return $this->render('static/pricing.html.twig');
    }
}
