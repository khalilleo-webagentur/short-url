<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Link;
use App\Entity\User;
use App\Repository\LinkRepository;
use DateTime;

final class LinkService
{
    public function __construct(
        private readonly LinkRepository $linkRepository,
    ) {
    }

    public function getById(int $id): ?User
    {
        return $this->linkRepository->find($id);
    }

    public function getByToken(string $token): ?Link
    {
        return $this->linkRepository->findOneBy(['token' => $token]);
    }

    /**
     * @return Link[]
     */
    public function getAllByUser(User $user): array
    {
        return $this->linkRepository->findBy(['user' => $user], ['id' => 'DESC']);
    }

    public function save(Link $model): Link
    {
        $this->linkRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }
}
