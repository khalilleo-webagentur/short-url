<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MaliciousUrl;
use App\Entity\User;
use App\Repository\MaliciousUrlRepository;
use DateTime;

final class MaliciousUrlsService
{
    public function __construct(
        private readonly MaliciousUrlRepository $maliciousUrlRepository,
    ) {
    }

    public function getById(int $id): ?MaliciousUrl
    {
        return $this->maliciousUrlRepository->find($id);
    }

    public function getOneByUrl(string $url): ?MaliciousUrl
    {
        return $this->maliciousUrlRepository->findOneBy(['url' => $url]);
    }

    public function getCount():string
    {
        $count = $this->maliciousUrlRepository->count();

        return number_format($count, 2, ',', '.');
    }

    /**
     * @return MaliciousUrl[]
     */
    public function getOneByCounter(): array
    {
        return $this->maliciousUrlRepository->findAllByCounter();
    }

    public function getByUserAndId(User $user, int $id): ?MaliciousUrl
    {
        return $this->maliciousUrlRepository->findOneBy(['user' => $user, 'id' => $id]);
    }

    public function getOneByUserAndUrl(User $user, string $url): ?MaliciousUrl
    {
        return $this->maliciousUrlRepository->findOneBy(['user' => $user, 'url' => $url]);
    }

    /**
     * @return MaliciousUrl[]
     */
    public function getAllByUser(User $user): array
    {
        return $this->maliciousUrlRepository->findBy(['user' => $user], ['id' => 'DESC']);
    }

    /**
     * @return MaliciousUrl[]
     */
    public function getAll(): array
    {
        return $this->maliciousUrlRepository->findBy([], ['id' => 'DESC']);
    }

    public function save(MaliciousUrl $model): MaliciousUrl
    {
        $this->maliciousUrlRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }

    public function delete(MaliciousUrl $model): void
    {
        $this->maliciousUrlRepository->remove($model, true);
    }
}
