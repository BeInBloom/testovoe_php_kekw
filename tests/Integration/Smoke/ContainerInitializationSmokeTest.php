<?php

declare(strict_types=1);

namespace Tests\Integration\Smoke;

use App\Domain\Contracts\LoggerInterface;
use App\Infrastructure\Container;
use PHPUnit\Framework\TestCase;

final class ContainerInitializationSmokeTest extends TestCase {
    public function test_container_initialization_resolves_logger_service(): void {
        $tempLogPath      = sys_get_temp_dir() . '/news-site-smoke.log';
        $originalLogPath  = $_ENV['LOG_PATH'] ?? null;
        $_ENV['LOG_PATH'] = $tempLogPath;

        try {
            $container = new Container();
            $logger    = $container->get(LoggerInterface::class);

            self::assertInstanceOf(LoggerInterface::class, $logger);
        } finally {
            if ($originalLogPath !== null) {
                $_ENV['LOG_PATH'] = $originalLogPath;
            } else {
                unset($_ENV['LOG_PATH']);
            }
        }
    }
}
