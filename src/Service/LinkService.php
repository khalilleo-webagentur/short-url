<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Link;
use App\Entity\LinkCollection;
use App\Entity\User;
use App\Repository\LinkRepository;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class LinkService
{
    public function __construct(
        private LinkRepository        $linkRepository,
        private LinkCollectionService $linkCollectionService
    ) {
    }

    public function getById(int $id): ?Link
    {
        return $this->linkRepository->find($id);
    }

    public function getByUserAndId(User|UserInterface $user, int $id): ?Link
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
    public function getAllByUserAndCollection(User|UserInterface $user, LinkCollection $collection): array
    {
        return $this->linkRepository->findBy(['user' => $user, 'collection' => $collection], ['isFave' => 'DESC']);
    }

    public function removeCollectionFromLinks(User|UserInterface $user, LinkCollection $collection): void
    {
        foreach ($this->getAllByUserAndCollection($user, $collection) as $link) {
            $this->save($link->setCollection(null));
        }
    }

    public function deleteCollectionWithLinks(User|UserInterface $user, LinkCollection $collection): void
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

    public function getOneByUserAndUrl(User|UserInterface $user, string $url): ?Link
    {
        return $this->linkRepository->findOneBy(['user' => $user, 'url' => $url]);
    }

    /**
     * @return Link[]
     */
    public function getAllByUser(User|UserInterface $user): array
    {
        return $this->linkRepository->findBy(['user' => $user], ['isFave' => 'DESC', 'id' => 'DESC']);
    }

    public function getCountLinksByUser(User|UserInterface $user): int
    {
        return count($this->linkRepository->findBy(['user' => $user]));
    }

    /**
     * @return Link[]
     */
    public function getAllWithoutAnyAssociation(User $user): array
    {
        return $this->linkRepository->findBy(['user' => $user, 'collection' => null], ['id' => 'DESC']);
    }

    /**
     * @return Link[]
     */
    public function getAll(): array
    {
        return $this->linkRepository->findBy([], ['id' => 'DESC']);
    }

    public function moveLinksWithoutAnyAssociationsToCollection(User|UserInterface $user, LinkCollection $collection): int
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
    public function searchByUserAndTitle(User|UserInterface $user, string $title): array
    {
        return $this->linkRepository->searchByUserAndTitle($user, $title);
    }

    /**
     * @return Link[]
     */
    public function filterByUser(User|UserInterface $user, int $groupId, bool $isPublic, bool $hasClicks, bool $isFave): array
    {
        return $this->linkRepository->filterByUser($user, $groupId, $isPublic, $hasClicks, $isFave);
    }

    public function delete(Link $model): void
    {
        $this->linkRepository->remove($model, true);
    }
}
