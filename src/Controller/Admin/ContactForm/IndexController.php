<?php

declare(strict_types=1);

namespace App\Controller\Admin\ContactForm;

use App\Service\ContactFormService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/co65i0j2v6s5l0w1')]
class IndexController extends AbstractController
{
    use FormValidationTrait;

    private const string DASHBOARD_CONTACTS_ROUTE = 'app_dashboard_contacts_index';

    public function __construct(
        private readonly ContactFormService $contactFormService
    ) {
    }

    #[Route('/contacts', name: 'app_dashboard_contacts_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $contacts = $this->contactFormService->getAll();

        return $this->render('admin/contact-form/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/remove/{id}', name: 'app_dashboard_contact_remove_store', methods: ['POST'])]
    public function remove(?string $id): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $contact = $this->contactFormService->getById($this->validateNumber($id));

        if (!$contact) {
            $this->addFlash('warning', 'Contact not found.');
            return $this->redirectToRoute(self::DASHBOARD_CONTACTS_ROUTE);
        }

        $this->contactFormService->save($contact->setIsDeleted(true));

        return $this->redirectToRoute(self::DASHBOARD_CONTACTS_ROUTE);
    }
}
