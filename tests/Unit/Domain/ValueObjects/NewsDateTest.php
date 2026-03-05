<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\NewsDate;
use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class NewsDateTest extends TestCase
{
    public function test_from_string_creates_value_object(): void
    {
        $newsDate = NewsDate::fromString('2026-03-05 12:34:56');

        self::assertSame('2026-03-05 12:34:56', $newsDate->format());
    }

    public function test_from_string_throws_for_invalid_format(): void
    {
        $this->expectException(InvalidArgumentException::class);

        NewsDate::fromString('invalid-date');
    }

    public function test_from_datetime_creates_immutable_value_object(): void
    {
        $dateTime = new DateTime('2026-03-05 13:00:00');

        $newsDate = NewsDate::fromDateTime($dateTime);

        self::assertInstanceOf(DateTimeImmutable::class, $newsDate->getValue());
        self::assertSame('2026-03-05 13:00:00', $newsDate->format());
    }

    public function test_format_uses_custom_pattern(): void
    {
        $newsDate = NewsDate::fromString('2026-03-05 12:34:56');

        self::assertSame('05.03.2026', $newsDate->format('d.m.Y'));
    }
}
