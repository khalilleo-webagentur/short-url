<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SocialProfile;
use App\Entity\User;
use App\Repository\SocialProfileRepository;
use DateTime;

final readonly class SocialProfileService
{
    public function __construct(private SocialProfileRepository $socialShareRepository)
    {
    }

    public function getByUserAndId(User $user, int $id): ?SocialProfile
    {
        return $this->socialShareRepository->findOneBy(['user' => $user, 'id' => $id]);
    }

    public function getByUserAndUsername(User $user, string $username): ?SocialProfile
    {
        return $this->socialShareRepository->findOneBy(['user' => $user, 'username' => $username]);
    }

    /**
     * @return SocialProfile[]
     */
    public function getAllByUser(User $user): array
    {
        return $this->socialShareRepository->findBy(['user' => $user], ['username' => 'ASC']);
    }

    /**
     * @return SocialProfile[]
     */
    public function getAll(): array
    {
        return $this->socialShareRepository->findBy([], ['username' => 'ASC']);
    }

    public function add(User $user, string $platform, string $username, string $url): SocialProfile
    {
        $model = new SocialProfile();

        $model
            ->setUser($user)
            ->setPlatform($platform)
            ->setUsername($username)
            ->setUrl($url);

        $this->save($model);

        return $model;
    }

    public function save(SocialProfile $socialProfile): SocialProfile
    {
        $this->socialShareRepository->save($socialProfile->setUpdatedAt(new DateTime()), true);

        return $socialProfile;
    }

    public function delete(SocialProfile $socialProfile): void
    {
        $this->socialShareRepository->remove($socialProfile, true);
    }
}
