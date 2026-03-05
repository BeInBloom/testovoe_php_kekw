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
use DI\ContainerBuilder;
use RuntimeException;

final readonly class Container
{
    private \DI\Container $container;

    public function __construct()
    {
        $builder = new ContainerBuilder();

        $this->configureContainer($builder);

        $this->container = $builder->build();
    }

    public function get(string $id): mixed
    {
        return $this->container->get($id);
    }

    public function getContainer(): \DI\Container
    {
        return $this->container;
    }

    /**
     * @param ContainerBuilder<\DI\Container> $builder
     */
    private function configureContainer(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            LoggerInterface::class => fn () => new FileLogger(
                $_ENV['LOG_PATH'] ?? __DIR__ . '/../../storage/logs/app.log'
            ),

            Connection::class => function (\DI\Container $c): Connection {
                $logger = $c->get(LoggerInterface::class);

                if (!$logger instanceof LoggerInterface) {
                    throw new RuntimeException('Configured logger does not implement LoggerInterface');
                }

                return new Connection(
                    $_ENV['DB_HOST'] ?? 'mysql',
                    $_ENV['DB_NAME'] ?? 'news_db',
                    $_ENV['DB_USER'] ?? 'news_user',
                    $_ENV['DB_PASS'] ?? 'news_pass',
                    $logger
                );
            },

            NewsRepositoryInterface::class => function (\DI\Container $c): NewsRepository {
                $connection = $c->get(Connection::class);
                $logger = $c->get(LoggerInterface::class);

                if (!$connection instanceof Connection) {
                    throw new RuntimeException('Configured connection must be Connection instance');
                }

                if (!$logger instanceof LoggerInterface) {
                    throw new RuntimeException('Configured logger does not implement LoggerInterface');
                }

                return new NewsRepository($connection, $logger);
            },

            PaginationInterface::class => fn () => new PaginationService(),

            NewsServiceInterface::class => function (\DI\Container $c): NewsService {
                $newsRepository = $c->get(NewsRepositoryInterface::class);
                $pagination = $c->get(PaginationInterface::class);
                $logger = $c->get(LoggerInterface::class);

                if (!$newsRepository instanceof NewsRepositoryInterface) {
                    throw new RuntimeException('Configured repository does not implement NewsRepositoryInterface');
                }

                if (!$pagination instanceof PaginationInterface) {
                    throw new RuntimeException('Configured pagination does not implement PaginationInterface');
                }

                if (!$logger instanceof LoggerInterface) {
                    throw new RuntimeException('Configured logger does not implement LoggerInterface');
                }

                return new NewsService($newsRepository, $pagination, $logger);
            },
        ]);
    }
}
