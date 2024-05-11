<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\UserSetting;
use App\Repository\UserSettingRepository;
use DateTime;

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

    public function save(UserSetting $model): UserSetting
    {
        $this->userSettingRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }
}
