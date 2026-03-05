<?php

declare(strict_types=1);

namespace App\Infrastructure\Logging;

use App\Domain\Contracts\LoggerInterface;
use App\Infrastructure\Logging\Exceptions\LogDirectoryNotWritableException;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

final class FileLogger implements LoggerInterface {
    private Logger $logger;

    public function __construct(string $logFile) {
        $logDir = dirname($logFile);
        $this->ensureLogDirectoryExists($logDir);

        $this->logger = new Logger('news_site');
        $this->logger->pushHandler(new StreamHandler($logFile, Level::Debug));
    }

    /**
     * @param array<string, mixed> $context
     */
    public function info(string $message, array $context = []): void {
        $this->logger->info($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function error(string $message, array $context = []): void {
        $this->logger->error($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function warning(string $message, array $context = []): void {
        $this->logger->warning($message, $context);
    }

    private function ensureLogDirectoryExists(string $logDir): void {
        if (!is_dir($logDir)) {
            if (!@mkdir($logDir, 0755, true)) {
                throw new LogDirectoryNotWritableException(
                    'Cannot create log directory: ' . $logDir
                );
            }
        }

        if (!is_writable($logDir)) {
            throw new LogDirectoryNotWritableException(
                'Log directory is not writable: ' . $logDir
            );
        }
    }
}
