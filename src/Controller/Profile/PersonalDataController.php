<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile/personal-data/u7k0s9bkngf6dba7')]
class PersonalDataController extends AbstractController
{
    private const APP_PROFILE = 'app_profile';

    public function __construct(
        private readonly UserService $userService
    ) {
    }

    #[Route('/change-username', name: 'app_profile_personal_data_username_store', methods: 'POST')]
    public function storeUsername(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        // @todo

        $this->addFlash('notice', 'Not implemeted yet!');

        return $this->redirectToRoute(self::APP_PROFILE);
    }

    #[Route('/change-email', name: 'app_profile_personal_data_email_store', methods: 'POST')]
    public function storeEmail(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        // @todo

        $this->addFlash('notice', 'Not implemeted yet!');

        return $this->redirectToRoute(self::APP_PROFILE);
    }

    #[Route('/download-personal-data', name: 'app_profile_personal_data_download', methods: 'POST')]
    public function downloadPersonalData(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        // @todo

        $this->addFlash('notice', 'Not implemeted yet!');

        return $this->redirectToRoute(self::APP_PROFILE);
    }

    #[Route('/delete', name: 'app_profile_personal_data_delete', methods: 'POST')]
    public function delete(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $this->denyAccessUnlessGranted('ROLE_USER');

        // @todo

        $this->addFlash('notice', 'Not implemeted yet!');

        return $this->redirectToRoute(self::APP_PROFILE);
    }
}
