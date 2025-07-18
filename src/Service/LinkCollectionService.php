<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\LinkCollection;
use App\Entity\User;
use App\Repository\LinkCollectionRepository;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class LinkCollectionService
{
    public function __construct(
        private LinkCollectionRepository $linkCollectionRepository,
    ) {
    }

    public function getById(int $id): ?LinkCollection
    {
        return $this->linkCollectionRepository->find($id);
    }

    public function getByUserAndName(User|UserInterface $user, string $name): ?LinkCollection
    {
        return $this->linkCollectionRepository->findOneBy(['user' => $user, 'name' => $name]);
    }

    public function getByUserAndId(User|UserInterface $user, int $id): ?LinkCollection
    {
        return $this->linkCollectionRepository->findOneBy(['user' => $user, 'id' => $id]);
    }

    /**
     * @return LinkCollection[]
     */
    public function getAllByUser(?UserInterface $user): array
    {
        return $this->linkCollectionRepository->findBy(['user' => $user], ['id' => 'DESC']);
    }

    public function getOneByUserAndDefaultCollection(User|UserInterface $user): ?LinkCollection
    {
        return $this->linkCollectionRepository->findOneBy(['user' => $user, 'isDefault' => 1], ['id' => 'DESC']);
    }

    public function resetAll(User|UserInterface $user): void
    {
        foreach ($this->getAllByUser($user) as $model) {
            if ($model->isDefault()) {
                $this->save($model->setIsDefault(false));
            }
        }
    }

    public function resetAndUpdateDefault(User|UserInterface $user, LinkCollection $collection): void
    {
        $this->resetAll($user);
        $this->save($collection->setIsDefault(true));
    }

    /**
     * @return LinkCollection[]
     */
    public function getAll(): array
    {
        return $this->linkCollectionRepository->findBy([], ['id' => 'DESC']);
    }

    public function save(LinkCollection $model): LinkCollection
    {
        $this->linkCollectionRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }

    public function delete(LinkCollection $model): void
    {
        $this->linkCollectionRepository->remove($model, true);
    }
}
