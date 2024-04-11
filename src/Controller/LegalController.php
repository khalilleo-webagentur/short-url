<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    public function __construct(
        private readonly ConfigService $configService
    ) {
    }

    #[Route('/imprint/cS7cD7bA7rR9hA0gV6hE5rZ7lK2xW1dT', name: 'app_legal_imprint')]
    public function imprint(): Response
    {
        return $this->render('static/imprint.html.twig');
    }

    #[Route('/privacy-policy/nL3nA7kI9bZ6aC3dM4fM2aM2iY4dU0qP', name: 'app_legal_privacy_policy')]
    public function privacyPolicy(): Response
    {
        $street = $this->configService->getParameter('address_street');
        $plz = $this->configService->getParameter('adress_plz');
        $city = $this->configService->getParameter('adress_city');
        $country = $this->configService->getParameter('adress_country');
        $phonenumber = $this->configService->getParameter('phonenumber');
        $email = $this->configService->getParameter('legal_emal');

        return $this->render('static/privacy-policy.html.twig',  [
            'street' => $street,
            'plz' => $plz,
            'city' => $city,
            'country' => $country,
            'phonenumber' => $phonenumber,
            'email' => $email,
        ]);
    }
}
