<?php

declare(strict_types=1);

namespace App\Service\Export;

use App\Entity\User;
use App\Service\LinkCollectionService;
use App\Service\LinkService;
use App\Service\UserService;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserLinksExport
{
    public function __construct(
        private readonly UserService           $userService,
        private readonly LinkService           $linkService,
        private readonly LinkCollectionService $linkCollectionService
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
                'link_title',
                'collection_name',
                'link_token',
                'link_url',
                'link_clicks',
                'is_link_star',
                'is_link_public',
                'is_link_repoted',
                'link_updated_at',
                'link_created_at',
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
            'Collections' => $this->getUserLinkCollectionsTableData($user),
        ];
    }

    private function getUserLinksTableData(UserInterface|User $user): array
    {
        $links = $this->linkService->getAllByUser($user);
        $result = [];

        if (null !== $links) {
            foreach ($links as $row) {
                $result[] = [
                    'link_title' => $row->getTitle(),
                    'collection_name' => $row->getCollection() ? $row->getCollection()->getName() : null,
                    'link_token' => $row->getToken(),
                    'link_url' => $row->getUrl(),
                    'link_clicks' => $row->getCounter(),
                    'is_link_star' => $row->isFave(),
                    'is_link_public' => $row->isPublic(),
                    'is_link_repoted' => $row->isReported(),
                    'link_updated_at' => ($row->getUpdatedAt())->format('Y-m-d H:i:s'),
                    'link_created_at' => ($row->getCreatedAt())->format('Y-m-d H:i:s'),
                ];
            }
        }

        return $result;
    }

    private function getUserLinkCollectionsTableData(User $user): array
    {
        $collections = $this->linkCollectionService->getAllByUser($user);
        $result = [];

        if (null !== $collections) {
            foreach ($collections as $row) {
                $result[] = [
                    'collection_name' => $row->getName(),
                    'collection_updated_at' => ($row->getUpdatedAt())->format('Y-m-d H:i:s'),
                    'collection_created_at' => ($row->getCreatedAt())->format('Y-m-d H:i:s'),
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
