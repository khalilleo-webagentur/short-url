<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Helper\AppHelper;
use App\Service\Job\DeleteUserJob;
use App\Service\UserService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/dashboard/j2o5i0p7v6o5p4wb')]
class JobController extends AbstractController
{
    use FormValidationTrait;

    private const string ADMIN_USERS_ROUTE = 'app_admin_users_index';

    public function __construct(
        private readonly UserService $userService
    ) {
    }

    #[Route('/user/delete', name: 'app_admin_users_job_delete', methods: 'POST')]
    public function delete(Request $request, DeleteUserJob $deleteUserJob): RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $email = $this->validate($request->request->get('email'));

        if ($email && $targetUser = $this->userService->getByEmail($email)) {

            if (!in_array(AppHelper::ROLE_SUPER_ADMIN, $targetUser->getRoles(), true)) {
                $deleteUserJob->deleteByUser($targetUser)
                    ? $this->addFlash('success', 'User has been deleted successfully.')
                    : $this->addFlash('warning', 'User could not be deleted.');
            }

            return $this->redirectToRoute(self::ADMIN_USERS_ROUTE);
        }

        if ($this->validateCheckbox($request->request->get('spamUsers'))) {
            $countDeletedUsers = $deleteUserJob->emptyPin();
            $countDeletedUsers > 0
                ? $this->addFlash('success', sprintf('Users [%s] has been deleted successfully.', $countDeletedUsers))
                : $this->addFlash('warning', 'No Users has been deleted.');
            return $this->redirectToRoute(self::ADMIN_USERS_ROUTE);
        }

        $this->addFlash('notice', 'Nothing has been detected.');

        return $this->redirectToRoute(self::ADMIN_USERS_ROUTE);
    }

}
