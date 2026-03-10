<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use RuntimeException;

final class PageOutOfRangeException extends RuntimeException {
    public static function fromPage(int $page, int $lastPage): self {
        return new self("Page {$page} is out of range. Last available page is {$lastPage}.");
    }

    public static function forEmptyCollection(int $page): self {
        return new self("Page {$page} is out of range for an empty collection.");
    }
}
