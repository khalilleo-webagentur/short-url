<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Link;
use App\Entity\LinkStatistic;
use App\Repository\LinkStatisticRepository;
use App\Traits\RemoteTrait;
use DateTime;

final class LinkStatisticService
{
    use RemoteTrait;

    public function __construct(
        private readonly LinkStatisticRepository $linkStatisticRepository,
    ) {
    }

    public function create(Link $link): void
    {
        $model = new LinkStatistic();

        $this->save(
            $model
                ->setLink($link)
                ->setIpAddress($this->getRemote())
                ->setUserAgent($this->getAgent())
        );
    }

    public function save(LinkStatistic $model): LinkStatistic
    {
        $this->linkStatisticRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }
}
