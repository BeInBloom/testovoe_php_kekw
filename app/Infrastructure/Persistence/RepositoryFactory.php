<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Contracts\NewsRepositoryInterface;
use App\Infrastructure\Container;
use RuntimeException;

final readonly class RepositoryFactory
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function createNewsRepository(): NewsRepositoryInterface
    {
        $repository = $this->container->get(NewsRepositoryInterface::class);

        if (!$repository instanceof NewsRepositoryInterface) {
            throw new RuntimeException('Configured repository does not implement NewsRepositoryInterface');
        }

        return $repository;
    }
}
