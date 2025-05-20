<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Link;
use App\Service\LinkService;
use App\Service\TokenGeneratorService;
use App\Service\UserService;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// $ php bin/console app:urls

#[AsCommand(
    name: 'app:urls',
    description: 'Dummy URLs for demonstration purposes.',
    hidden: false
)]
class DummyURLsCommand extends Command
{
    public const FAILURE = 0;
    public const SUCCESS = 1;

    public function __construct(
        private readonly UserService           $userService,
        private readonly LinkService           $linkService,
        private readonly TokenGeneratorService $tokenGeneratorService
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('running ...');

        $faker = Factory::create();

        $user = $this->userService->getByEmail('dev@khalilleo.com');

        $i = 1;

        while ($i <= 50) {

            $link = new Link();

            $this->linkService->save(
                $link
                    ->setUser($user)
                    ->setTitle($faker->text(5))
                    ->setUrl($faker->url())
                    ->setToken($this->tokenGeneratorService->randomToken())
            );

            $i++;
        }

        $output->writeln('done!');

        return self::SUCCESS;
    }

    protected function configure(): void
    {
        //
    }
}
