<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\UserSetting;
use App\Repository\UserSettingRepository;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class UserSettingService
{
    public function __construct(
        private UserSettingRepository $userSettingRepository,
    ) {
    }

    public function getById(int $id): ?UserSetting
    {
        return $this->userSettingRepository->find($id);
    }

    public function getOneByUser(User $user): ?UserSetting
    {
        return $this->userSettingRepository->findOneBy(['user' => $user]);
    }

    /**
     * @return UserSetting[]
     */
    public function getAll(): array
    {
        return $this->userSettingRepository->findBy([], ['createdAt' => 'DESC']);
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
