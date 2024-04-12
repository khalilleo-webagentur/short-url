<?php

declare(strict_types=1);

namespace App\Controller;

use App\Mails\Admin\ContactFormNewMessageMail;
use App\Service\MonologService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactUsController extends AbstractController
{
    use FormValidationTrait;

    private const HOME_ROUTE = 'app_home';
    private const CONTECT_US_ROUTE = 'app_contact_us_index';

    public function __construct(
        private readonly MonologService $monolog
    ) {
    }

    #[Route('/contact-us/rN5jU2yW1rZ8lX3rS2lF9yD3xI6tY2cN', name: 'app_contact_us_index')]
    public function index(): Response
    {
        return $this->render('static/contact-us.html.twig');
    }

    #[Route('/contact-us-new/mT7kJ6eV3cZ0uB6jW9tY8hB5iW3yF6yC', name: 'app_contact_us_new', methods: 'POST')]
    public function new(Request $request, ContactFormNewMessageMail $contactFormNewMessageMail): Response
    {
        $name = $this->validate($request->request->get('iName'));

        $email = $this->validate($request->request->get('iEmail'));

        $subject = $this->validate($request->request->get('iSubject'));

        $message = $this->validateTextarea($request->request->get('iMessage'));

        if (!$name || !$email || !$subject || !$message) {
            $this->addFlash('warning', 'All fields are required.');
            return $this->redirectToRoute(self::CONTECT_US_ROUTE);
        }

        $this->monolog->logger->debug(
            sprintf('New Message from Contact Form: Name [%s], Email [%s], Subject [%s] and Message [%s]', $name, $email, $subject, $message)
        );

        $contactFormNewMessageMail->send([]);

        $this->addFlash('success', 'Thank you for contact us - will make a Response within 3 days.');

        return $this->redirectToRoute(self::HOME_ROUTE);
    }
}
