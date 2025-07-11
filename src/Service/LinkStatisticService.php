<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Link;
use App\Entity\LinkStatistic;
use App\Repository\LinkStatisticRepository;
use App\Traits\RemoteTrait;
use DateTime;
use Khalilleo\BrowserDetect\UserAgent;

final class LinkStatisticService
{
    use RemoteTrait;

    public function __construct(
        private readonly LinkStatisticRepository $linkStatisticRepository,
    ) {
    }

    public function getOneByLink(Link $link): ?LinkStatistic
    {
        return $this->linkStatisticRepository->findOneBy(['link' => $link], ['id' => 'DESC']);
    }

    /**
     * @return LinkStatistic[]
     */
    public function getAllByLink(Link $link): array
    {
        return $this->linkStatisticRepository->findBy(['link' => $link], ['id' => 'DESC']);
    }

    public function create(Link $link): void
    {
        $model = new LinkStatistic();

        $userAgent = new UserAgent();

        $this->save(
            $model
                ->setLink($link)
                ->setIpAddress($this->getRemote())
                ->setBrowserName($userAgent->getBrowserName())
                ->setBrowserLang($userAgent->getBrowserLang())
                ->setPlatform($userAgent->getPlatform())
                ->setReferer($userAgent->getReferer())
                ->setIsMobile($userAgent->isMobile())
        );
    }

    public function save(LinkStatistic $model): LinkStatistic
    {
        $this->linkStatisticRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }

    public function anonomize(Link $link): void
    {
        foreach ($this->getAllByLink($link) as $row) {
            if ($row->getIpAddress() !== '_anonomyzed') {
                $this->save($row->setIpAddress('_anonomyzed'));
            }
        }
    }

    public function deleteAllByLink(Link $model): int
    {
        $i = 0;

        if (count($model->getLinkStatistics()) > 0) {
            foreach ($model->getLinkStatistics() as $statistic) {
                $this->delete($statistic);
                $i++;
            }
        }

        return $i;
    }

    public function delete(LinkStatistic $model): void
    {
        $this->linkStatisticRepository->remove($model, true);
    }
}
