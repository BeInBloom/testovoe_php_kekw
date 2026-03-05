<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use RuntimeException;

final class NewsNotFoundException extends RuntimeException {
    public static function byId(int $id): self {
        return new self("News with ID {$id} not found");
    }

    public static function latest(): self {
        return new self('No news found in database');
    }
}
