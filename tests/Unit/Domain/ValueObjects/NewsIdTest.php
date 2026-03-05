<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\NewsId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class NewsIdTest extends TestCase
{
    public function test_create_valid_news_id(): void
    {
        $newsId = new NewsId(1);

        $this->assertEquals(1, $newsId->getValue());
    }

    public function test_create_news_id_with_zero_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new NewsId(0);
    }

    public function test_create_news_id_with_negative_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new NewsId(-1);
    }

    public function test_news_id_equals_returns_true_for_same_id(): void
    {
        $id1 = new NewsId(1);
        $id2 = new NewsId(1);

        $this->assertTrue($id1->equals($id2));
    }

    public function test_news_id_equals_returns_false_for_different_id(): void
    {
        $id1 = new NewsId(1);
        $id2 = new NewsId(2);

        $this->assertFalse($id1->equals($id2));
    }
}
