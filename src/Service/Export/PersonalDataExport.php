<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\User;
use App\Service\UserService;
use DateTimeInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class PersonalDataExport
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    public function asJson(UserInterface|User $user): bool|string
    {
        $this->prepareJsonHeader($user->getName());

        return json_encode($this->exportByUser($user), JSON_PRETTY_PRINT);
    }

    private function exportByUser(UserInterface|User $user): array
    {
        return [
            'account' => $this->getUserTableData($user),
        ];
    }

    private function getUserTableData(UserInterface|User $user): array
    {
        $row = $this->userService->getById($user->getId());
        $result = [];

        if (null !== $row) {
            $result = [
                'user_name' => $row->getName(),
                'email_address' => $row->getEmail(),
                'user_role' => strtolower(str_replace('_', '-', $row->getRoles()[0])),
                'is_account_verified' => $row->isVerified(),
                'is_account_deleted' => $row->isDeleted(),
                'last_updated_at' => ($row->getUpdatedAt())->format(DateTimeInterface::ATOM),
                'account_created_at' => ($row->getCreatedAt())->format(DateTimeInterface::ATOM),
            ];
        }

        return $result;
    }

    private function prepareJsonHeader(string $username): void
    {
        $file = ucfirst($username) . '_Personal_Data_' . date('Ymd_His') . '.json';

        header("Content-Type: application/json");
        header("Content-Disposition: attachment; filename=" . htmlspecialchars($file));
        header("Pragma: no-cache");
        header("Expires: 0");
    }
}
