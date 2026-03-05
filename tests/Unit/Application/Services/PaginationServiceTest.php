<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\PaginationService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PaginationServiceTest extends TestCase {
    public function test_get_current_page_returns_default_value(): void {
        $service = new PaginationService();

        self::assertSame(1, $service->getCurrentPage());
        self::assertSame(3, $service->getCurrentPage(3));
    }

    public function test_get_total_pages_returns_ceil_value(): void {
        $service = new PaginationService();

        self::assertSame(3, $service->getTotalPages(10, 4));
        self::assertSame(0, $service->getTotalPages(0, 4));
    }

    public function test_get_total_pages_throws_on_invalid_per_page(): void {
        $service = new PaginationService();

        $this->expectException(InvalidArgumentException::class);

        $service->getTotalPages(10, 0);
    }

    public function test_has_next_page_behaviour(): void {
        $service = new PaginationService();

        self::assertTrue($service->hasNextPage(1, 10, 4));
        self::assertFalse($service->hasNextPage(3, 10, 4));
    }

    public function test_has_next_page_throws_on_invalid_per_page(): void {
        $service = new PaginationService();

        $this->expectException(InvalidArgumentException::class);

        $service->hasNextPage(1, 10, 0);
    }
}
