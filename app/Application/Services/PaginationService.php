<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Contracts\PaginationInterface;
use InvalidArgumentException;

final readonly class PaginationService implements PaginationInterface {
    public function getTotalPages(int $total, int $perPage): int {
        if ($perPage <= 0) {
            throw new InvalidArgumentException('Items per page must be positive');
        }

        if ($total < 0) {
            throw new InvalidArgumentException('Total items cannot be negative');
        }

        return (int) ceil($total / $perPage);
    }

    public function hasNextPage(int $currentPage, int $total, int $perPage): bool {
        if ($perPage <= 0) {
            throw new InvalidArgumentException('Items per page must be positive');
        }

        if ($currentPage <= 0) {
            throw new InvalidArgumentException('Current page must be positive');
        }

        $totalPages = $this->getTotalPages($total, $perPage);

        return $currentPage < $totalPages;
    }
}
