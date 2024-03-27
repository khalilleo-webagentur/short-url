<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MonologService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AbuseController extends AbstractController
{
    use FormValidationTrait;

    private const ABOUSE_ROUTE = 'app_report_abuse';
    private const HOME_ROUTE = 'app_home';

    public function __construct(
        private readonly MonologService $monolog
    ) {
    }

    #[Route('/report-abuse/kK4oY1iC2rL0nP9zR5pF2wP4qW0cY3hG', name: 'app_report_abuse')]
    public function index(): Response
    {
        return $this->render('static/report-abuse.html.twig');
    }

    #[Route('/report-new-abuse/qD5xJ0oH6hW4fF3xD2cE3qA1jE6jB7rL', name: 'app_report_abuse_new', methods: 'POST')]
    public function new(Request $request): Response
    {
        $link = $this->validate($request->request->get('iMaliciousLink'));

        $option = $this->validate($request->request->get('sMaliciousOption'));

        if (!$link || !$option || !in_array(ucfirst($option), ['Spam', 'Phishing', 'Malicious', 'Porn', 'Other'], true)) {
            $this->addFlash('warning', 'Malicious link and option are required.');
            return $this->redirectToRoute(self::ABOUSE_ROUTE);
        }

        $message = $this->validateTextarea($request->request->get('iMessage'));

        $this->monolog->logger->debug(
            sprintf('Malicious Link: Link [%s], Option [%s] and Message [%s]', $link, $option, $message)
        );

        // @TODO send webmaster an email to be informed ..

        $this->addFlash('success', 'Malicious link has been reported. It will be deleted immediately after a Review.');

        return $this->redirectToRoute(self::HOME_ROUTE);
    }
}
