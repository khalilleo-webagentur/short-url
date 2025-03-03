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
use Symfony\Component\Security\Core\User\UserInterface;

final class SocialProfileStatisticsService
{
    use RemoteTrait;

    public function __construct(
        private readonly SocialProfileStatisticsRepository $socialProfileStatisticsRepository,
        private readonly SocialProfileService              $socialProfileService,
    ) {
    }

    public function getByUserAndId(User|UserInterface $user, int $id): ?SocialProfileStatistics
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
    public function getAllBySocialProfileAndUser(SocialProfile $socialProfile, User|UserInterface $user): array
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
                ->setSeen(false)
        );

        $this->socialProfileService->save($socialProfile->setStatisticsSeen(false));
    }

    public function getAllNotSeenYet(User $user, SocialProfile $socialProfile): array
    {
        return $this->socialProfileStatisticsRepository->findBy([
            'user' => $user,
            'socialProfile' => $socialProfile,
            'isSeen' => 0
        ]);
    }

    public function hasNotSeenStatisticsYet(User $user, SocialProfile $socialProfile): bool
    {
        $isNotSeenYet = count($this->getAllNotSeenYet($user, $socialProfile)) > 0;

        if ($isNotSeenYet) {
            $this->socialProfileService->save($socialProfile->setStatisticsSeen(true));
        }

        return $isNotSeenYet;
    }

    public function markAllAsSeen(User|UserInterface $user, SocialProfile $socialProfile): void
    {
        if ($statistics = $this->getAllNotSeenYet($user, $socialProfile)) {

            $this->socialProfileService->save($socialProfile->setStatisticsSeen(true));

            foreach ($statistics as $statistic) {
                /** @var SocialProfileStatistics $statistic */
                $this->save(
                    $statistic->setSeen(true)
                );
            }
        }
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
