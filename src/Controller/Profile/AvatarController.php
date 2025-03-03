<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Helper\AppHelper;
use App\Service\Core\FileHandlerService;
use App\Service\Core\FileUploaderService;
use App\Service\LinkService;
use App\Service\ProfileService;
use App\Service\SocialProfileSettingService;
use App\Traits\FormValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile/avatar/u7k7s5b5n7i6d9a7')]
class AvatarController extends AbstractController
{
    use FormValidationTrait;

    private const PROFILE_ROUTE = 'app_profile';

    public function __construct(
        private readonly LinkService                 $linkService,
        private readonly ProfileService              $profileService,
        private readonly SocialProfileSettingService $socialProfileSettingService,
    ) {
    }

    #[Route('/update', name: 'app_profile_change_avatar', methods: 'POST')]
    public function uploadAvatar(
        Request             $request,
        FileUploaderService $uploader,
        FileHandlerService  $fileHandler
    ): Response
    {

        // if (!$this->isCsrfTokenValid('authenticate', $this->validate($request->request->get('_csrf_token')))) {
        //     $this->addFlash('warning', 'CSRF Token is not valid.');
        //     return $this->redirectToRoute(self::HOME_ROUTE);
        // }

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $profile = $this->profileService->getByUser($user);

        $path = $this->getParameter('avatarDir');

        if ($profile) {
            if ($this->validateCheckbox($request->request->get('removeAvatar'))) {
                // delete the avatar at first
                $fileHandler->unlinkFile($path, $profile->getAvatarName());
                // then reset it in profile table
                $this->profileService->update($profile, AppHelper::DEFAULT_AVATAR_NAME, 0, null);
                $this->addFlash('success', 'Avatar has been deleted');

                return $this->redirectToRoute(self::PROFILE_ROUTE);
            }

            /** @var UploadedFile $file */
            $file = $request->files->get('avatar');

            if (!$file) {
                $this->addFlash('warning', 'Please choose a valid image');
                return $this->redirectToRoute(self::PROFILE_ROUTE);
            }

            $ext = $file->getClientOriginalExtension();
            $size = (int)$file->getSize();

            if (!$fileHandler->isImageExtensionAllowed($ext)) {
                $this->addFlash('warning', 'Extension is not allowed');
                return $this->redirectToRoute(self::PROFILE_ROUTE);
            }

            // update the avatar
            if ($profile->getAvatarName() !== AppHelper::DEFAULT_AVATAR_NAME) {
                // delete the old one at first
                $fileHandler->unlinkFile($path, $profile->getAvatarName());
                // upload the new one
                $fileName = $uploader->upload($file);
                $this->profileService->update($profile, $fileName, $size, $ext);
                $this->addFlash('success', 'Avatar has been updated');

                return $this->redirectToRoute(self::PROFILE_ROUTE);
            }

            $fileName = $uploader->upload($file);
            $this->profileService->update($profile, $fileName, $size, $ext);
            $this->addFlash('success', 'Avatar has been uploaded');

            return $this->redirectToRoute(self::PROFILE_ROUTE);
        }

        $this->addFlash('warning', 'Failed to upload avatar');

        return $this->redirectToRoute(self::PROFILE_ROUTE);
    }
}
