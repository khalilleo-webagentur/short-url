<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Link;
use App\Entity\LinkCollection;
use App\Entity\User;
use App\Repository\LinkRepository;
use DateTime;

final class LinkService
{
    public function __construct(
        private readonly LinkRepository $linkRepository,
        private readonly LinkCollectionService $linkCollectionService
    ) {
    }

    public function getById(int $id): ?Link
    {
        return $this->linkRepository->find($id);
    }

    public function getByUserAndId(User $user, int $id): ?Link
    {
        return $this->linkRepository->findOneBy(['user' => $user, 'id' => $id]);
    }

    public function getByUserAndToken(User $user, string $token): ?Link
    {
        return $this->linkRepository->findOneBy(['user' => $user, 'token' => $token]);
    }

    /**
     * @return Link[]
     */
    public function getAllByUserAndCollection(User $user, LinkCollection $collection): array
    {
        return $this->linkRepository->findBy(['user' => $user, 'collection' => $collection], ['isFave' => 'DESC']);
    }

    public function removeCollectionFromLinks(User $user, LinkCollection $collection): void
    {
        foreach ($this->getAllByUserAndCollection($user, $collection) as $link) {
            $this->save($link->setCollection(null));
        }
    }

    public function deleteCollectionWithLinks(User $user, LinkCollection $collection): void
    {
        foreach ($this->getAllByUserAndCollection($user, $collection) as $link) {
            $this->delete($link);
        }

        $this->linkCollectionService->delete($collection);
    }

    public function getByToken(string $token): ?Link
    {
        return $this->linkRepository->findOneBy(['token' => $token]);
    }

    public function getOneByUserAndUrl(User $user, string $url): ?Link
    {
        return $this->linkRepository->findOneBy(['user' => $user, 'url' => $url]);
    }

    /**
     * @return Link[]
     */
    public function getAllByUser(User $user): array
    {
        return $this->linkRepository->findBy(['user' => $user], ['isFave' => 'DESC', 'id' => 'DESC']);
    }

    public function getCountLinksByUser(User $user): int
    {
        return count($this->linkRepository->findBy(['user' => $user]));
    }

    /**
     * @return Link[]
     */
    public function getAllWithoutAnyAssociation(User $user): array
    {
        return $this->linkRepository->findBy(['user' => $user ,'collection' => null], ['id' => 'DESC']);
    }

    /**
     * @return Link[]
     */
    public function getAll(): array
    {
        return $this->linkRepository->findBy([], ['id' => 'DESC']);
    }

    public function moveLinksWithoutAnyAssociationsToCollection(User $user, LinkCollection $collection): int
    {
        $i = 0;

        foreach ($this->getAllWithoutAnyAssociation($user) as $link) {
            $this->save($link->setCollection($collection));
            $i++;
        }

        return $i;
    }

    public function moveLinksToCollection(User $user, LinkCollection $collection): int
    {
        $i = 0;

        foreach ($this->getAllByUser($user) as $link) {
            $this->save($link->setCollection($collection));
            $i++;
        }

        return $i;
    }

    public function save(Link $model): Link
    {
        $this->linkRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }

    /**
     * @return Link[]
     */
    public function searchByUserAndTitle(User $user, string $title): array
    {
        return $this->linkRepository->searchByUserAndTitle($user, $title);
    }

    public function delete(Link $model): void
    {
        $this->linkRepository->remove($model, true);
    }
}
