<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/mJ2uO8oH6tF5kY5a')]
class IndexController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    #[Route('/users/home', name: 'app_admin_users_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $users = $this->userService->getAll();

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }
}
