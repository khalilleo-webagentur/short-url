<?php

declare(strict_types=1);

namespace App\Controller\Profile\Dashboard\Setting;

use App\Service\LinkCollectionService;
use App\Service\LinkService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/p/d/setting/job/k4k7j0gdd1t8q24b')]
class JobController extends AbstractController
{
    use FormValidationTrait;

    private const PROFILE_USER_SETTING_ROUTE = 'app_profile_dashboard_setting_index';

    public function __construct(
        private readonly LinkService $linkService,
        private readonly LinkCollectionService $linkCollectionService
    ) {
    }
}
