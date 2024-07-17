<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Profile;
use App\Helper\AppHelper;
use App\Repository\ProfileRepository;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

final class ProfileService
{
    public function __construct(
        private readonly ProfileRepository $profileRepository
    ) {
    }

    public function getByUser(UserInterface $user): ?Profile
    {
        return $this->profileRepository->findOneBy(['user' => $user]);
    }

    public function add(UserInterface $user): Profile
    {
        $entity = new Profile();
        $entity
            ->setUser($user)
            ->setAvatarName(AppHelper::DEFAULT_AVATAR_NAME);

        $this->save($entity);

        return $entity;
    }

    public function update(Profile $entity, string $name, int $size, ?string $ext): Profile
    {
        $entity
            ->setAvatarName($name)
            ->setSize($size)
            ->setExtention($ext);

        $this->save($entity);

        return $entity;
    }

    public function save(Profile $entity): Profile
    {
        $this->profileRepository->save($entity->setUpdatedAt(new DateTime()), true);

        return $entity;
    }
}