<?php

declare(strict_types=1);

namespace App\Presentation\Http;

use InvalidArgumentException;

final class QueryIntReader {
    /**
     * @param array<string, mixed> $query
     */
    public static function positiveInt(array $query, string $key, ?int $default = null): int {
        if (!array_key_exists($key, $query) || $query[$key] === '' || $query[$key] === null) {
            if ($default !== null) {
                return $default;
            }

            throw new InvalidArgumentException("Missing required query parameter: {$key}");
        }

        $rawValue = $query[$key];

        if (is_int($rawValue)) {
            return self::assertPositive($rawValue, $key);
        }

        if (!is_string($rawValue) || preg_match('/^[1-9][0-9]*$/', $rawValue) !== 1) {
            throw new InvalidArgumentException("Query parameter {$key} must be a positive integer");
        }

        return self::assertPositive((int) $rawValue, $key);
    }

    private static function assertPositive(int $value, string $key): int {
        if ($value <= 0) {
            throw new InvalidArgumentException("Query parameter {$key} must be a positive integer");
        }

        return $value;
    }
}
