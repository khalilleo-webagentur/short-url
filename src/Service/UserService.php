<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class UserService
{
    public function __construct(
        private MonologService $monologService,
        private UserRepository $userRepository,
    ) {
    }

    public function getById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function getByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function getByToken(string $token): ?User
    {
        return $this->userRepository->findOneBy(['token' => $token]);
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->findBy([], ['id' => 'DESC']);
    }

    /**
     * @return User[]
     */
    public function getAllDeletedUsers(): array
    {
        return $this->userRepository->findBy(['isDeleted' => true], ['id' => 'DESC']);
    }

    public function hasUserRequestedNewSecurityCode(): bool
    {
        $users = $this->userRepository->findTheLastRecentToken();
        return count($users) > 0;
    }

    public function save(User|UserInterface $model): ?User
    {
        $this->userRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }

    public function encodePassword(string $text): string
    {
        return password_hash($text, PASSWORD_DEFAULT);
    }

    public function isPasswordValid(User $user, string $text): bool
    {
        return password_verify($text, $user->getPassword());
    }

    public function delete(User|UserInterface $user): bool
    {
        try {
            $userId = $user->getId();
            $this->userRepository->remove($user, true);
            return true;
        } catch (Exception $e) {
            $this->monologService->logger->error(
                sprintf(
                    "The user with ID: %s could not be deleted. \n Error: %s",
                    $userId,
                    $e->getMessage()
                )
            );
            return false;
        }
    }
}
