<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\User;
use App\Service\LinkService;
use App\Service\UserService;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserLinksExport
{
    public function __construct(
        private readonly UserService $userService,
        private readonly LinkService $linkService,
    ) {
    }

    public function asJson(UserInterface|User $user): bool|string
    {
        $this->prepareJsonHeader($user->getName());

        return json_encode($this->exportAsJsonByUser($user), JSON_PRETTY_PRINT);
    }

    public function asCSV(UserInterface|User $user): void
    {
        $filename = ucfirst($user->getName()) . '_exported_links_as_CSV_' . date('Ymd_His') . '.csv';

        $this->prepareCsvHeader($filename);

        $rows = $this->getUserLinksTableData($user);

        $separator = ';';

        if (!empty($rows)) {

            $keys = [
                '_link_title',
                '_link_token',
                '_link_url',
                '_link_clicks',
                '_is_link_public',
                '_is_link_repoted',
                '_link_updated_at',
                '_link_created_at'
            ];

            echo implode($separator, $keys) . "\n";

            foreach ($rows as $row) {
                echo implode($separator, $row) . "\n";
            }
        }

        exit();
    }

    private function exportAsJsonByUser(UserInterface|User $user): array
    {
        return [
            'URLs' => $this->getUserLinksTableData($user),
        ];
    }

    private function getUserLinksTableData(UserInterface|User $user): array
    {
        $links = $this->linkService->getAllByUser($user);
        $result = [];

        if (null !== $links) {
            foreach ($links as $row) {
                $result[] = [
                    '_link_title' => $row->getTitle(),
                    '_link_token' => $row->getToken(),
                    '_link_url' => $row->getUrl(),
                    '_link_clicks' => $row->getCounter(),
                    '_is_link_public' => $row->isPublic(),
                    '_is_link_repoted' => $row->isReported(),
                    '_link_updated_at' => ($row->getUpdatedAt())->format('Y-m-d H:i:s'),
                    '_link_created_at' => ($row->getCreatedAt())->format('Y-m-d H:i:s'),
                ];
            }
        }

        return $result;
    }

    private function prepareJsonHeader(string $username): void
    {
        $file = ucfirst($username) . '_exported_links_as_JSON_' . date('Ymd_His') . '.json';

        header("Content-Type: application/json");
        header("Content-Disposition: attachment; filename=" . htmlspecialchars($file));
        header("Pragma: no-cache");
        header("Expires: 0");
    }

    private function prepareCsvHeader(string $filename): void
    {
        header("Content-Type: application/csv");
        header("Content-Disposition: attachment; filename=" . htmlspecialchars($filename));
        header("Pragma: no-cache");
        header("Expires: 0");
    }
}
