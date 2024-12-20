<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\UserSetting;
use App\Repository\UserSettingRepository;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserSettingService
{
    public function __construct(
        private readonly UserSettingRepository $userSettingRepository,
    ) {
    }

    public function getOneByUser(User $user): ?UserSetting
    {
        return $this->userSettingRepository->findOneBy(['user' => $user]);
    }

    public function allowDuplicatedUrls(User|UserInterface $user): bool
    {
        $setting = $this->getOneByUser($user);

        return $setting && $setting->isAllowDuplicatedUrls();
    }

    public function allowLinkAlias(User $user): bool
    {
        $setting = $this->getOneByUser($user);

        return $setting && $setting->isAllowLinkAlias();
    }

    public function allowRedirectAfterNewLink(User $user): bool
    {
        $setting = $this->getOneByUser($user);

        return $setting && $setting->allowRedirectAfterNewLink();
    }

    public function resetPrivateClicks(User $user): bool
    {
        $setting = $this->getOneByUser($user);

        return $setting && true === $setting->isResetPrivateClicks();
    }

    public function save(UserSetting $model): UserSetting
    {
        $this->userSettingRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }
}
