<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\SocialProfileSettingService;
use App\Service\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// $ php bin/console app:new-social-rofile-setting

#[AsCommand(
    name: 'app:new-social-rofile-setting',
    description: 'Add demo social profile setting for demonstration purposes.',
    hidden: false
)]
class SocialProfileSettingCommand extends Command
{
    public const FAILURE = 0;
    public const SUCCESS = 1;

    public function __construct(
        private readonly UserService                 $userService,
        private readonly SocialProfileSettingService $socialProfileSettingService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('running ...');

        if ($user = $this->userService->getByEmail('dev@khalilleo.com')) {

            $this->socialProfileSettingService->add($user, $user->getName());

            return self::SUCCESS;
        }

        $output->writeln('Social profile setting cannot be created ...');

        return self::FAILURE;
    }

    protected function configure(): void
    {
        //
    }
}
