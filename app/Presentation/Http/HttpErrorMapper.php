<?php

declare(strict_types=1);

namespace App\Presentation\Http;

use App\Domain\Exceptions\NewsNotFoundException;
use InvalidArgumentException;
use Throwable;

final class HttpErrorMapper {
    public function map(Throwable $exception): HttpError {
        if ($exception instanceof InvalidArgumentException) {
            return new HttpError(400, 'Invalid request parameters.');
        }

        if ($exception instanceof NewsNotFoundException) {
            return new HttpError(404, 'News not found.');
        }

        return new HttpError(500, 'Internal server error.');
    }
}
