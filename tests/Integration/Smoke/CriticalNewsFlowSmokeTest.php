<?php

declare(strict_types=1);

namespace Tests\Integration\Smoke;

use App\Application\Services\NewsService;
use App\Domain\Contracts\LoggerInterface;
use App\Domain\Contracts\NewsRepositoryInterface;
use App\Domain\Contracts\PaginationInterface;
use App\Domain\Entities\News;
use App\Domain\Exceptions\NewsNotFoundException;
use App\Domain\ValueObjects\NewsDate;
use App\Domain\ValueObjects\NewsId;
use PHPUnit\Framework\TestCase;

final class CriticalNewsFlowSmokeTest extends TestCase {
    public function test_can_get_news_list_for_first_page(): void {
        $news = [
            $this->createNews(1, 'Alpha'),
            $this->createNews(2, 'Beta'),
            $this->createNews(3, 'Gamma'),
            $this->createNews(4, 'Delta'),
            $this->createNews(5, 'Epsilon'),
        ];

        $service = new NewsService(
            new SmokeNewsRepository($news),
            new SmokePagination(),
            new SmokeLogger()
        );

        $list = $service->getNewsList(1);

        self::assertCount(4, $list->news);
        self::assertSame('Alpha', $list->news[0]->title);
        self::assertTrue($list->hasNextPage);
    }

    private function createNews(int $id, string $title): News {
        return new News(
            new NewsId($id),
            NewsDate::fromString('2026-03-05 12:00:00'),
            $title,
            $title . ' announce',
            $title . ' content',
            'image.jpg'
        );
    }
}

/**
 * @internal
 */
final class SmokeNewsRepository implements NewsRepositoryInterface {
    /** @var array<int, News> */
    private array $news;

    /**
     * @param array<int, News> $news
     */
    public function __construct(array $news) {
        $this->news = array_values($news);
    }

    public function getById(NewsId $id): News {
        foreach ($this->news as $item) {
            if ($item->getId()->equals($id)) {
                return $item;
            }
        }

        throw NewsNotFoundException::byId($id->getValue());
    }

    public function getLatest(): News {
        if ($this->news === []) {
            throw NewsNotFoundException::latest();
        }

        return $this->news[0];
    }

    /**
     * @return array<int, News>
     */
    public function getPaginated(int $page, int $perPage): array {
        return array_slice($this->news, ($page - 1) * $perPage, $perPage);
    }

    public function getTotalCount(): int {
        return count($this->news);
    }
}

/**
 * @internal
 */
final class SmokePagination implements PaginationInterface {
    public function getTotalPages(int $total, int $perPage): int {
        return (int) ceil($total / $perPage);
    }

    public function hasNextPage(int $currentPage, int $total, int $perPage): bool {
        return $currentPage < $this->getTotalPages($total, $perPage);
    }
}

/**
 * @internal
 */
final class SmokeLogger implements LoggerInterface {
    /**
     * @param array<string, mixed> $context
     */
    public function info(string $message, array $context = []): void {}

    /**
     * @param array<string, mixed> $context
     */
    public function error(string $message, array $context = []): void {}

    /**
     * @param array<string, mixed> $context
     */
    public function warning(string $message, array $context = []): void {}
}
