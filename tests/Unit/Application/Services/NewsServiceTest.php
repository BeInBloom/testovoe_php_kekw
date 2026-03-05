<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\NewsService;
use App\Domain\Contracts\LoggerInterface;
use App\Domain\Contracts\NewsRepositoryInterface;
use App\Domain\Contracts\PaginationInterface;
use App\Domain\Entities\News;
use App\Domain\Exceptions\NewsNotFoundException;
use App\Domain\ValueObjects\NewsDate;
use App\Domain\ValueObjects\NewsId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class NewsServiceTest extends TestCase
{
    public function test_get_latest_news_returns_mapped_dto(): void
    {
        $repository = new InMemoryNewsRepository([$this->createNews(1, 'Latest')]);
        $service = new NewsService($repository, new FakePagination(), new InMemoryLogger());

        $latest = $service->getLatestNews();

        self::assertSame(1, $latest->id);
        self::assertSame('Latest', $latest->title);
    }

    public function test_get_news_list_returns_paginated_result(): void
    {
        $repository = new InMemoryNewsRepository([
            $this->createNews(1, 'First'),
            $this->createNews(2, 'Second'),
            $this->createNews(3, 'Third'),
            $this->createNews(4, 'Fourth'),
            $this->createNews(5, 'Fifth'),
        ]);
        $service = new NewsService($repository, new FakePagination(), new InMemoryLogger());

        $list = $service->getNewsList(1);

        self::assertSame(1, $list->currentPage);
        self::assertSame(2, $list->totalPages);
        self::assertTrue($list->hasNextPage);
        self::assertCount(4, $list->news);
        self::assertSame('First', $list->news[0]->title);
    }

    public function test_get_news_list_with_invalid_page_throws_exception(): void
    {
        $repository = new InMemoryNewsRepository([$this->createNews(1, 'Only')]);
        $service = new NewsService($repository, new FakePagination(), new InMemoryLogger());

        $this->expectException(InvalidArgumentException::class);

        $service->getNewsList(0);
    }

    public function test_get_news_detail_returns_requested_news(): void
    {
        $repository = new InMemoryNewsRepository([
            $this->createNews(1, 'First'),
            $this->createNews(2, 'Second'),
        ]);
        $service = new NewsService($repository, new FakePagination(), new InMemoryLogger());

        $detail = $service->getNewsDetail(new NewsId(2));

        self::assertSame(2, $detail->id);
        self::assertSame('Second', $detail->title);
    }

    private function createNews(int $id, string $title): News
    {
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
final class InMemoryNewsRepository implements NewsRepositoryInterface
{
    /** @var array<int, News> */
    private array $news;

    /**
     * @param array<int, News> $news
     */
    public function __construct(array $news)
    {
        $this->news = array_values($news);
    }

    public function getById(NewsId $id): News
    {
        foreach ($this->news as $item) {
            if ($item->getId()->equals($id)) {
                return $item;
            }
        }

        throw NewsNotFoundException::byId($id->getValue());
    }

    public function getLatest(): News
    {
        if ($this->news === []) {
            throw NewsNotFoundException::latest();
        }

        return $this->news[0];
    }

    /**
     * @return array<int, News>
     */
    public function getPaginated(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;

        return array_slice($this->news, $offset, $perPage);
    }

    public function getTotalCount(): int
    {
        return count($this->news);
    }
}

/**
 * @internal
 */
final class FakePagination implements PaginationInterface
{
    public function getCurrentPage(): int
    {
        return 1;
    }

    public function getTotalPages(int $total, int $perPage): int
    {
        return (int) ceil($total / $perPage);
    }

    public function hasNextPage(int $currentPage, int $total, int $perPage): bool
    {
        return $currentPage < $this->getTotalPages($total, $perPage);
    }
}

/**
 * @internal
 */
final class InMemoryLogger implements LoggerInterface
{
    /** @var list<array{level: string, message: string, context: array<string, mixed>}> */
    public array $entries = [];

    /**
     * @param array<string, mixed> $context
     */
    public function info(string $message, array $context = []): void
    {
        $this->entries[] = ['level' => 'info', 'message' => $message, 'context' => $context];
    }

    /**
     * @param array<string, mixed> $context
     */
    public function error(string $message, array $context = []): void
    {
        $this->entries[] = ['level' => 'error', 'message' => $message, 'context' => $context];
    }

    /**
     * @param array<string, mixed> $context
     */
    public function warning(string $message, array $context = []): void
    {
        $this->entries[] = ['level' => 'warning', 'message' => $message, 'context' => $context];
    }
}
