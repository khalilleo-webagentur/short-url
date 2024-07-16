<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SocialProfileSetting;
use App\Entity\SocialProfileVisitor;
use App\Entity\User;
use App\Repository\SocialProfileVisitorRepository;
use App\Service\Core\BrowserDetectService;
use DateTime;

final class SocialProfileVisitorService
{
    public function __construct(
        private readonly SocialProfileVisitorRepository $socialProfileVisitorRepository,
        private readonly BrowserDetectService $browserDetectService,
        private readonly SocialProfileSettingService $socialProfileSettingService,
    ) {
    }

    public function getOneByUser(User $user): ?SocialProfileVisitor
    {
        return $this->socialProfileVisitorRepository->findOneBy(['user' => $user]);
    }

    /**
     * @return SocialProfileVisitor[]
     */
    public function getAllByUser(User $user): array
    {
        return $this->socialProfileVisitorRepository->findBy(['user' => $user]);
    }

    public function add(User $user, SocialProfileSetting $socialProfileSetting): ?SocialProfileVisitor
    {
        if ($this->getOneByUser($user)) {
            return null;
        }

        $model = new SocialProfileVisitor();

        $userAgent = $this->browserDetectService->userAgent();
        $userAgentUuid = sha1($userAgent->getBrowserName() . $userAgent->getPlatform() . $userAgent->getBrowserLang());

        $model
            ->setUser($user)
            ->setVisitorUuid($userAgentUuid);

        $this->save($model);

        $this->socialProfileSettingService->save(
            $socialProfileSetting->setCountViews($socialProfileSetting->getCountViews() + 1)
        );

        return $model;
    }

    public function save(SocialProfileVisitor $model): SocialProfileVisitor
    {
        $this->socialProfileVisitorRepository->save($model->setUpdatedAt(new DateTime()), true);

        return $model;
    }

    public function delete(SocialProfileVisitor $model): void
    {
        $this->socialProfileVisitorRepository->remove($model, true);
    }
}
