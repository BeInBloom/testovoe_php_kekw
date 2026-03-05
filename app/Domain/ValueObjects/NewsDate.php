<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;

final readonly class NewsDate
{
    public DateTimeImmutable $value;

    public function __construct(DateTimeImmutable $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $dateString): self
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateString);

        if ($date === false) {
            throw new InvalidArgumentException('Invalid date format. Expected: Y-m-d H:i:s');
        }

        return new self($date);
    }

    public static function fromDateTime(DateTime $date): self
    {
        return new self(DateTimeImmutable::createFromMutable($date));
    }

    public function getValue(): DateTimeImmutable
    {
        return $this->value;
    }

    public function format(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->value->format($format);
    }
}
