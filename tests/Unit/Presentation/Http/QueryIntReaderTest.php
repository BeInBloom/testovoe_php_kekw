<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Http;

use App\Presentation\Http\QueryIntReader;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class QueryIntReaderTest extends TestCase {
    public function test_positive_int_returns_default_when_key_is_missing(): void {
        self::assertSame(1, QueryIntReader::positiveInt([], 'page', 1));
    }

    public function test_positive_int_parses_string_value(): void {
        self::assertSame(12, QueryIntReader::positiveInt(['id' => '12'], 'id'));
    }

    public function test_positive_int_rejects_non_digit_input(): void {
        $this->expectException(InvalidArgumentException::class);

        QueryIntReader::positiveInt(['page' => '1abc'], 'page');
    }

    public function test_positive_int_rejects_array_input(): void {
        $this->expectException(InvalidArgumentException::class);

        QueryIntReader::positiveInt(['page' => ['2']], 'page');
    }
}
