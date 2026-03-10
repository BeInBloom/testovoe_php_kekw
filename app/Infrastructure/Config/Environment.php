<?php

declare(strict_types=1);

namespace App\Infrastructure\Config;

final class Environment {
    public static function load(string $projectRoot): void {
        $envFile = rtrim($projectRoot, '/\\') . '/.env';

        if (!is_readable($envFile)) {
            return;
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!is_array($lines)) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$name, $value] = array_pad(explode('=', $line, 2), 2, '');
            $name           = trim($name);

            if ($name === '') {
                continue;
            }

            $existingValue = getenv($name);

            if ($existingValue !== false) {
                $_ENV[$name] = $existingValue;
                $_SERVER[$name] ??= $existingValue;

                continue;
            }

            $value = self::stripWrappingQuotes(trim($value));

            putenv($name . '=' . $value);
            $_ENV[$name]    = $value;
            $_SERVER[$name] = $_SERVER[$name] ?? $value;
        }
    }

    private static function stripWrappingQuotes(string $value): string {
        if (
            strlen($value) >= 2
            && (($value[0] === '"' && $value[strlen($value) - 1] === '"')
            || ($value[0] === '\'' && $value[strlen($value) - 1] === '\''))
        ) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
