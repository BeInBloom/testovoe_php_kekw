<?php

declare(strict_types=1);

namespace App\Infrastructure\Logging;

use App\Domain\Contracts\LoggerInterface;
use App\Infrastructure\Container;
use RuntimeException;

final readonly class LoggerFactory
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function createLogger(): LoggerInterface
    {
        $logger = $this->container->get(LoggerInterface::class);

        if (!$logger instanceof LoggerInterface) {
            throw new RuntimeException('Configured logger does not implement LoggerInterface');
        }

        return $logger;
    }
}
