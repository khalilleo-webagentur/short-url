<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

final readonly class ConfigService
{
    public function __construct(
        private ContainerInterface $container,
        private MonologService     $monolog
    ) {
    }

    public function getParameter(string $key): string
    {
        try {
            return $this->container->getParameter($key);
        } catch (Exception $e) {
            $this->monolog->logger->critical($e->getTraceAsString());
            return "";
        }
    }
}
