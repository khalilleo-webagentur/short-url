<?php

declare(strict_types=1);

namespace App\Controller;

use App\Mails\Admin\NotifyAbuseLinkMail;
use App\Service\MonologService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AbuseController extends AbstractController
{
    use FormValidationTrait;

    private const string ABUSE_ROUTE = 'app_report_abuse';
    private const string HOME_ROUTE = 'app_home';

    public function __construct(
        private readonly MonologService $monolog
    ) {
    }

    #[Route('/report-abuse/q6j0a6x8s9d2e4h3', name: 'app_report_abuse')]
    public function index(): Response
    {
        return $this->render('static/report-abuse.html.twig');
    }

    #[Route('/report-new-abuse/i7r6q0u0y4k2m5a8', name: 'app_report_abuse_new', methods: 'POST')]
    public function new(Request $request, NotifyAbuseLinkMail $notifyAbuseLinkMail): Response
    {
        $link = $this->validate($request->request->get('iMaliciousLink'));

        $option = $this->validate($request->request->get('sMaliciousOption'));

        if (!$link || !$option || !in_array(ucfirst($option), ['Spam', 'Phishing', 'Malicious', 'Porn', 'Other'], true)) {
            $this->addFlash('warning', 'Malicious link and option are required.');
            return $this->redirectToRoute(self::ABUSE_ROUTE);
        }

        $message = $this->validateTextarea($request->request->get('iMessage'));

        $this->monolog->logger->debug(
            sprintf('Malicious Link: Link [%s], Option [%s] and Message [%s]', $link, $option, $message)
        );

        $notifyAbuseLinkMail->send([]);

        $this->addFlash('success', 'Malicious link has been reported. It will be deleted immediately after a Review.');

        return $this->redirectToRoute(self::HOME_ROUTE);
    }
}
