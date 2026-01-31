<?php

declare(strict_types=1);

namespace App\Service\Job;

use App\Entity\User;
use App\Service\LinkCollectionService;
use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Service\MonologService;
use App\Service\SocialProfileService;
use App\Service\SocialProfileSettingService;
use App\Service\SocialProfileStatisticsService;
use App\Service\SocialProfileVisitorService;
use App\Service\TempUserService;
use App\Service\UserService;
use Exception;

final readonly class DeleteUserJob
{
    public function __construct(
        private UserService                    $userService,
        private LinkStatisticService           $linkStatisticService,
        private MonologService                 $monologService,
        private LinkService                    $linkService,
        private TempUserService                $tempUserService,
        private LinkCollectionService          $linkCollectionService,
        private SocialProfileService           $socialProfileService,
        private SocialProfileSettingService    $socialProfileSettingService,
        private SocialProfileVisitorService    $socialProfileVisitorService,
        private SocialProfileStatisticsService $socialProfileStatisticsService,
    ) {
    }

    public function deleteByUser(User $user): bool
    {
        $userId = $user->getId();

        try {
            if ($tempUser = $this->tempUserService->getByUser($user)) {
                $this->tempUserService->delete($tempUser);
            }

            $userLinks = $user->getLinks();

            if (count($userLinks) > 0) {
                foreach ($userLinks as $link) {
                    $linkStatistics = $this->linkStatisticService->getAllByLink($link);
                    if (count($linkStatistics) > 0) {
                        foreach ($linkStatistics as $statistic) {
                            $this->linkStatisticService->delete($statistic);
                        }
                    }
                    $this->linkService->delete($link);
                }
            }

            $linkCollections = $user->getLinkCollections();

            if (count($linkCollections) > 0) {
                foreach ($linkCollections as $linkCollection) {
                    $this->linkCollectionService->delete($linkCollection);
                }
            }

            $socialProfileStatistics = $user->getSocialProfileStatistics();

            if (count($socialProfileStatistics) > 0) {
                foreach ($socialProfileStatistics as $socialProfileStatistic) {
                    $this->socialProfileStatisticsService->delete($socialProfileStatistic);
                }
            }

            $socialProfileVisitors = $user->getSocialProfileVisitors();

            if (count($socialProfileVisitors) > 0) {
                foreach ($socialProfileVisitors as $socialProfileVisitor) {
                    $this->socialProfileVisitorService->delete($socialProfileVisitor);
                }
            }

            $socialProfile = $this->socialProfileService->getAllByUser($user);

            if (count($socialProfile) > 0) {
                foreach ($socialProfile as $profile) {
                    $this->socialProfileService->delete($profile);
                }
            }

            $socialProfileSetting = $this->socialProfileSettingService->getByUser($user);

            if (null !== $socialProfileSetting) {
                $this->socialProfileSettingService->delete($socialProfileSetting);
            }

            return $this->userService->delete($user);

        } catch (Exception $e) {
            $this->monologService->logger->error(
                sprintf(
                    "The user with ID: %s and all related data could not be deleted. \n Error: %s",
                    $userId,
                    $e->getMessage()
                )
            );
            return false;
        }
    }

    public function emptyPin(): int
    {
        $i = 0;

        foreach ($this->userService->getAllDeletedUsers() as $user) {
            if ($this->deleteByUser($user)) {
                ++$i;
            }
        }

        return $i;
    }
}
