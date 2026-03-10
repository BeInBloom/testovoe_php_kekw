<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\News;
use App\Domain\ValueObjects\NewsDate;
use App\Domain\ValueObjects\NewsId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class NewsTest extends TestCase {
    public function test_create_valid_news_entity(): void {
        $news = new News(
            new NewsId(1),
            NewsDate::fromString('2026-03-05 12:00:00'),
            'Title',
            'Announce',
            'Content',
            'image.jpg'
        );

        self::assertSame(1, $news->getId()->getValue());
        self::assertSame('2026-03-05 12:00:00', $news->getDate()->format());
        self::assertSame('Title', $news->getTitle());
        self::assertSame('Announce', $news->getAnnounce());
        self::assertSame('Content', $news->getContent());
        self::assertSame('image.jpg', $news->getImage());
    }

    public function test_create_news_with_empty_title_throws_exception(): void {
        $this->expectException(InvalidArgumentException::class);

        new News(
            new NewsId(1),
            NewsDate::fromString('2026-03-05 12:00:00'),
            ' ',
            'Announce',
            'Content',
            'image.jpg'
        );
    }

    public function test_create_news_allows_empty_optional_fields(): void {
        $news = new News(
            new NewsId(1),
            NewsDate::fromString('2026-03-05 12:00:00'),
            'Title',
            '',
            '',
            ''
        );

        self::assertSame('', $news->getAnnounce());
        self::assertSame('', $news->getContent());
        self::assertSame('', $news->getImage());
    }
}
