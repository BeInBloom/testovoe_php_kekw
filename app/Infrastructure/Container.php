<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\Contracts\NewsServiceInterface;
use App\Application\Services\NewsService;
use App\Application\Services\PaginationService;
use App\Domain\Contracts\LoggerInterface;
use App\Domain\Contracts\NewsRepositoryInterface;
use App\Domain\Contracts\PaginationInterface;
use App\Infrastructure\Logging\FileLogger;
use App\Infrastructure\Persistence\MySQL\Connection;
use App\Infrastructure\Persistence\MySQL\NewsRepository;
use App\Presentation\Controllers\IndexController;
use App\Presentation\Controllers\NewsController;
use RuntimeException;

final class Container {
    /** @var array<string, mixed> */
    private array $instances = [];

    public function get(string $id): mixed {
        return $this->instances[$id] ??= $this->create($id);
    }

    private function create(string $id): mixed {
        return match ($id) {
            LoggerInterface::class => new FileLogger(
                $this->env('LOG_PATH', __DIR__ . '/../../storage/logs/app.log')
            ),
            Connection::class => new Connection(
                $this->env('DB_HOST', 'mysql'),
                $this->env('DB_NAME', 'news_db'),
                $this->env('DB_USER', 'news_user'),
                $this->env('DB_PASS', 'news_pass'),
                $this->logger()
            ),
            NewsRepositoryInterface::class => new NewsRepository($this->connection(), $this->logger()),
            PaginationInterface::class     => new PaginationService(),
            NewsServiceInterface::class    => new NewsService(
                $this->newsRepository(),
                $this->pagination(),
                $this->logger()
            ),
            IndexController::class => new IndexController($this->newsService(), $this->logger()),
            NewsController::class  => new NewsController($this->newsService(), $this->logger()),
            default                => throw new RuntimeException("Unknown service requested: {$id}"),
        };
    }

    private function logger(): LoggerInterface {
        $logger = $this->get(LoggerInterface::class);

        if (!$logger instanceof LoggerInterface) {
            throw new RuntimeException('Configured logger does not implement LoggerInterface');
        }

        return $logger;
    }

    private function connection(): Connection {
        $connection = $this->get(Connection::class);

        if (!$connection instanceof Connection) {
            throw new RuntimeException('Configured connection must be Connection instance');
        }

        return $connection;
    }

    private function newsRepository(): NewsRepositoryInterface {
        $repository = $this->get(NewsRepositoryInterface::class);

        if (!$repository instanceof NewsRepositoryInterface) {
            throw new RuntimeException('Configured repository does not implement NewsRepositoryInterface');
        }

        return $repository;
    }

    private function pagination(): PaginationInterface {
        $pagination = $this->get(PaginationInterface::class);

        if (!$pagination instanceof PaginationInterface) {
            throw new RuntimeException('Configured pagination does not implement PaginationInterface');
        }

        return $pagination;
    }

    private function newsService(): NewsServiceInterface {
        $service = $this->get(NewsServiceInterface::class);

        if (!$service instanceof NewsServiceInterface) {
            throw new RuntimeException('Configured news service does not implement NewsServiceInterface');
        }

        return $service;
    }

    private function env(string $name, string $default): string {
        $value = $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name);

        return is_string($value) && $value !== '' ? $value : $default;
    }
}
