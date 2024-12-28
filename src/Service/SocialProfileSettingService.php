<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SocialProfileSetting;
use App\Entity\User;
use App\Repository\SocialProfileSettingRepository;
use DateTime;

final class SocialProfileSettingService
{
    public function __construct(
        private readonly SocialProfileSettingRepository $socialProfileSettingRepository
    ){
    }

    public function getById(int $id): ?SocialProfileSetting
    {
        return $this->socialProfileSettingRepository->find($id);
    }

    public function getByUser(User $user): ?SocialProfileSetting
    {
        return $this->socialProfileSettingRepository->findOneBy(['user' => $user]);
    }

    public function getByName(string $name): ?SocialProfileSetting
    {
        return $this->socialProfileSettingRepository->findOneBy(['mainName' => $name]);
    }

    /**
     * @return SocialProfileSetting[]
     */
    public function getAll(): array
    {
        return $this->socialProfileSettingRepository->findBy([], ['mainName' => 'ASC']);
    }

    public function add(User $user, string $name): SocialProfileSetting
    {
        $model = new SocialProfileSetting();

        $model
            ->setUser($user)
            ->setMainName($name)
            ->setDescription("User social profile")
            ->setCreatedAt(new DateTime());

        $this->save($model);

        return $model;
    }

    public function save(SocialProfileSetting $socialProfileSetting): SocialProfileSetting
    {
        $this->socialProfileSettingRepository->save(
            $socialProfileSetting
                ->setUpdatedAt(new DateTime()),
            true
        );

        return $socialProfileSetting;
    }

    public function delete(SocialProfileSetting $socialProfileSetting): void
    {
        $this->socialProfileSettingRepository->remove($socialProfileSetting, true);
    }
}
