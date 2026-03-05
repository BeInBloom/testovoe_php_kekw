<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class NewsId {
    public int $value;

    public function __construct(int $value) {
        $this->validate($value);

        $this->value = $value;
    }

    public function getValue(): int {
        return $this->value;
    }

    public function equals(NewsId $other): bool {
        return $this->value === $other->value;
    }

    private function validate(int $value): void {
        if ($value <= 0) {
            throw new InvalidArgumentException('News ID must be positive');
        }
    }
}
