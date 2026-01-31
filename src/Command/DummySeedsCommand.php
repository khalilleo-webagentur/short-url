<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Link;
use App\Entity\LinkCollection;
use App\Entity\LinkStatistic;
use App\Entity\MaliciousUrl;
use App\Entity\SocialProfile;
use App\Entity\TempUser;
use App\Entity\User;
use App\Entity\UserSetting;
use App\Service\LinkCollectionService;
use App\Service\LinkService;
use App\Service\LinkStatisticService;
use App\Service\MaliciousUrlsService;
use App\Service\ProfileService;
use App\Service\SocialProfileService;
use App\Service\SocialProfileSettingService;
use App\Service\TempUserService;
use App\Service\TokenGeneratorService;
use App\Service\UserService;
use App\Service\UserSettingService;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// $ php bin/console app:seeds

#[AsCommand(
    name: 'app:seeds',
    description: 'Dummy data for demonstration purposes.',
    hidden: false
)]
class DummySeedsCommand extends Command
{
    public const int FAILURE = 0;
    public const int SUCCESS = 1;

    public function __construct(
        private readonly UserService                 $userService,
        private readonly UserSettingService          $userSettingService,
        private readonly SocialProfileSettingService $socialProfileSettingService,
        private readonly SocialProfileService        $socialProfileService,
        private readonly ProfileService              $profileService,
        private readonly LinkService                 $linkService,
        private readonly LinkCollectionService       $linkCollectionService,
        private readonly LinkStatisticService        $linkStatisticService,
        private readonly MaliciousUrlsService        $maliciousUrlsService,
        private readonly TempUserService             $tempUserService,
        private readonly TokenGeneratorService       $tokenGeneratorService
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('running ...');

        $faker = Factory::create();
        $email = $faker->email;

        $user = $this->userService->getByEmail($email);

        if (!$user) {
            $username = str_replace(' ', '_', $faker->name('M'));
            $user = new User();
            $this->userService->save(
                $user
                    ->setName($username)
                    ->setEmail($email)
                    ->setPassword($this->userService->encodePassword($email))
                    ->setRoles(['ROLE_USER'])
                    ->setIsVerified(true)
                    ->setToken($this->tokenGeneratorService->randomToken())
            );

            $this->userSettingService->save(new UserSetting()->setUser($user));

            $this->socialProfileSettingService->add($user, $username);

            $this->profileService->add($user);

            // Link Collections
            $countLinkCollections = 1;

            while ($countLinkCollections <= 5) {
                $linkCollection = new LinkCollection();
                $collectionName = $faker->word;
                $linkCollection
                    ->setUser($user)
                    ->setName($collectionName);
                $this->linkCollectionService->save($linkCollection);
                $countLinkCollections++;
            }

            // Link
            $countLinks = 1;

            while ($countLinks <= 7) {
                $link = new Link();
                $this->linkService->save(
                    $link
                        ->setUser($user)
                        ->setTitle($faker->text(5))
                        ->setUrl($faker->url())
                        ->setToken($this->tokenGeneratorService->randomToken())
                );

                // Link Statistics
                $linkStatistic = new LinkStatistic();
                $linkStatistic
                    ->setBrowserName('Chrome')
                    ->setBrowserLang($faker->languageCode)
                    ->setPlatform('Linux')
                    ->setReferer('')
                    ->setLink($link)
                    ->setIsMobile(false)
                    ->setIpAddress($faker->ipv4);
                $this->linkStatisticService->save($linkStatistic);

                $countLinks++;
            }

            // Social Profile
            $profileName = str_replace(' ', '_', $faker->name('W'));
            $socialProfile = new SocialProfile();
            $this->socialProfileService->save(
                $socialProfile
                    ->setUser($user)
                    ->setPlatform('Twitter')
                    ->setUsername($profileName)
                    ->setUrl('https://x.com/' . $profileName)
            );

            // Malicious URLs
            $maliciousUrlCount = 1;

            while ($maliciousUrlCount <= 4) {
                $maliciousUrl = new MaliciousUrl();
                $this->maliciousUrlsService->save(
                    $maliciousUrl
                        ->setDomain($faker->domainName)
                        ->setUrl($faker->url)
                );
                $maliciousUrlCount++;
            }

            // Temp User
            $tempUser = new TempUser();
            $tempUser
                ->setEmail($email)
                ->setToken($this->tokenGeneratorService->randomTokenForVerification())
                ->setUser($user);
            $this->tempUserService->save($tempUser);
        }

        $output->writeln('done!');

        return self::SUCCESS;
    }

    protected function configure(): void
    {
        //
    }
}
