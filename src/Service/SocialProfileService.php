<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SocialProfile;
use App\Entity\User;
use App\Repository\SocialProfileRepository;
use DateTime;

final class SocialProfileService
{
    public function __construct(private readonly SocialProfileRepository $socialShareRepository)
    {
    }

    public function getById(int $id): ?SocialProfile
    {
        return $this->socialShareRepository->find($id);
    }

    public function getByUserAndName(User $user, string $name): ?SocialProfile
    {
        return $this->socialShareRepository->findOneBy(['user' => $user, 'name' => $name]);
    }

    /**
     * @return SocialProfile[]
     */
    public function getAll(): array
    {
        return $this->socialShareRepository->findBy([], ['name' => 'ASC']);
    }

    public function getPreparedSocialLinks(string $url, string $text): array
    {
        $results = [];

        foreach ($this->getAll() as $row) {
            $results[] = [
                'name' => $row->getName(),
                'icon' => $row->getIcon(),
                'color' => $row->getColor(),
                'url' => str_replace(['URL', 'TEXT'], [$url, $text], $row->getUrl()),
            ];
        }

        return $results;
    }

    public function add(User $user, string $name, string $icon, string $color, string $url): SocialProfile
    {
        $model = new SocialProfile();

        $model
            ->setUser($user)
            ->setName($name)
            ->setIcon($icon)
            ->setColor($color)
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
