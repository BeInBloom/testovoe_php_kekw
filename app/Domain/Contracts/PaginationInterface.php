<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

interface PaginationInterface {
    public function getCurrentPage(): int;
    public function getTotalPages(int $total, int $perPage): int;
    public function hasNextPage(int $currentPage, int $total, int $perPage): bool;
}
