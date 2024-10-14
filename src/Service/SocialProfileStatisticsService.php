<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SocialProfile;
use App\Entity\SocialProfileStatistics;
use App\Repository\SocialProfileStatisticsRepository;
use App\Traits\RemoteTrait;
use DateTime;
use App\Entity\User;
use Khalilleo\BrowserDetect\UserAgent;

final class SocialProfileStatisticsService
{
    use RemoteTrait;

    public function __construct(
        private readonly SocialProfileStatisticsRepository $socialProfileStatisticsRepository,
    ) {
    }

    public function getByUserAndId(User $user, int $id): ?SocialProfileStatistics
    {
        return $this->socialProfileStatisticsRepository->findOneBy(['user' => $user, 'id' => $id]);
    }

    /**
     * @return SocialProfileStatistics[]
     */
    public function getAllByUser(User $user): array
    {
        return $this->socialProfileStatisticsRepository->findBy(['user' => $user], ['id' => 'DESC']);
    }

    /**
     * @return SocialProfileStatistics[]
     */
    public function getAllBySocialProfileAndUser(SocialProfile $socialProfile, User $user): array
    {
        return $this->socialProfileStatisticsRepository->findBy(['socialProfile' => $socialProfile, 'user' => $user], ['id' => 'DESC']);
    }

    public function create(User $user, SocialProfile $socialProfile): void
    {
        $model = new SocialProfileStatistics();

        $userAgent = new UserAgent();

        $this->save(
            $model
                ->setUser($user)
                ->setSocialProfile($socialProfile)
                ->setIpAdress($this->getRemote())
                ->setBrowserName($userAgent->getBrowserName())
                ->setBrowserLang($userAgent->getBrowserLang())
                ->setPlatform($userAgent->getPlatform())
                ->setMobil($userAgent->isMobile())
        );
    }

    public function save(SocialProfileStatistics $model): SocialProfileStatistics
    {
        $this->socialProfileStatisticsRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }

    public function deleteAllByUser(User $user): void
    {
        foreach ($this->getAllByUser($user) as $statistic) {
            $this->delete($statistic);
        }
    }
    
    public function deleteAllByUserAndSocialProfile($user, $socialProfile): void
    {
        foreach ($this->getAllBySocialProfileAndUser($socialProfile, $user) as $statistic) {
            $this->delete($statistic);
        }
    }

    public function delete(SocialProfileStatistics $model): void
    {
        $this->socialProfileStatisticsRepository->remove($model, true);
    }
}
