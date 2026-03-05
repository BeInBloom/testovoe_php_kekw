<?php

declare(strict_types=1);

namespace App\Presentation\Http;

final readonly class HttpError
{
    public function __construct(
        public int $statusCode,
        public string $clientMessage
    ) {
    }
}
