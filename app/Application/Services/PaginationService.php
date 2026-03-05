<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Contracts\PaginationInterface;

final readonly class PaginationService implements PaginationInterface
{
    public function getCurrentPage(int $defaultPage = 1): int
    {
        return $defaultPage;
    }

    public function getTotalPages(int $total, int $perPage): int
    {
        if ($perPage <= 0) {
            throw new \InvalidArgumentException('Items per page must be positive');
        }

        return (int) ceil($total / $perPage);
    }

    public function hasNextPage(int $currentPage, int $total, int $perPage): bool
    {
        if ($perPage <= 0) {
            throw new \InvalidArgumentException('Items per page must be positive');
        }

        $totalPages = $this->getTotalPages($total, $perPage);

        return $currentPage < $totalPages;
    }
}
