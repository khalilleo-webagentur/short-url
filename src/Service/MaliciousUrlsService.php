<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MaliciousUrl;
use App\Entity\User;
use App\Repository\MaliciousUrlRepository;
use DateTime;

final readonly class MaliciousUrlsService
{
    public function __construct(
        private MaliciousUrlRepository $maliciousUrlRepository,
        private MonologService         $monolog
    ){
    }

    public function getById(int $id): ?MaliciousUrl
    {
        return $this->maliciousUrlRepository->find($id);
    }

    public function getOneByDomain(string $domain): ?MaliciousUrl
    {
        return $this->maliciousUrlRepository->findOneBy(['domain' => $domain]);
    }

    public function getOneByUrl(string $url): ?MaliciousUrl
    {
        return $this->maliciousUrlRepository->findOneBy(['url' => $url]);
    }

    public function isMaliciousUrl(string $url): bool
    {
        $parseUrl = parse_url($url);
        $domain = null;

        if (is_string($parseUrl)) {
            $domain = $parseUrl;
        } elseif (is_array($parseUrl) && isset($parseUrl['host'])) {
            $domain = $parseUrl['host'];
        } elseif (is_array($parseUrl) && isset($parseUrl['path']) && !isset($parseUrl['host'])) {
            $domain = $parseUrl['path'];
        }

        if ($maliciousUrl = $this->getOneByDomain($domain)) {

            $this->monolog->logger->debug(
                sprintf(
                    'Malicious URL: %s: %s',
                    $maliciousUrl->getId(),
                    $maliciousUrl->getUrl())
            );

            $this->save($maliciousUrl->setCounter($maliciousUrl->getCounter() + 1));

            return true;
        }

        return false;
    }

    public function updateDomains(int $limit): int
    {
        $i = 0;
        $maliciousUrls = $this->maliciousUrlRepository->findBy(['domain' => null], ['id' => 'DESC'], $limit);

        foreach ($maliciousUrls as $maliciousUrl) {

            $parseUrl = parse_url($maliciousUrl->getUrl());

            if (is_string($parseUrl)) {
                $this->save($maliciousUrl->setDomain($parseUrl));
                $i++;
            } elseif (is_array($parseUrl) && isset($parseUrl['host'])) {
                $this->save($maliciousUrl->setDomain($parseUrl['host']));
                $i++;
            } elseif (is_array($parseUrl) && isset($parseUrl['path']) && !isset($parseUrl['host'])) {
                $this->save($maliciousUrl->setDomain($parseUrl['path']));
                $i++;
            }
        }

        return $i;
    }

    public function getCount(): string
    {
        $count = $this->maliciousUrlRepository->count();

        return number_format($count, 2, ',', '.');
    }

    /**
     * @return MaliciousUrl[]
     */
    public function getAllByCounter(): array
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

    /**
     * @return MaliciousUrl[]
     */
    public function search(string $keyword): array
    {
        return $this->maliciousUrlRepository->findAllByKeyword($keyword);
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
