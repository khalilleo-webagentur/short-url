<?php

declare(strict_types=1);

namespace App\Controller\Admin\SocialProfile;

use App\Service\SocialProfileService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/social-profile/i3s8i3r8e2o2l2d5')]
class IndexController extends AbstractController
{
    use FormValidationTrait;

    private const ADMIN_SOCIAL_PROFILE_ROUTE = 'app_admin_social_profile_index';

    public function __construct(
        private readonly SocialProfileService $socialProfileService,
    ) {
    }

    #[Route('/home', name: 'app_admin_social_profile_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $socialProfiles = $this->socialProfileService->getAll();

        return $this->render('admin/social-profiles/index.html.twig', [
            'socialProfiles' => $socialProfiles,
        ]);
    }
}
