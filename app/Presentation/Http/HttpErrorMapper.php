<?php

declare(strict_types=1);

namespace App\Presentation\Http;

use App\Domain\Exceptions\NewsNotFoundException;
use App\Domain\Exceptions\PageOutOfRangeException;
use InvalidArgumentException;
use Throwable;

final class HttpErrorMapper {
    public function map(Throwable $exception): HttpError {
        if ($exception instanceof InvalidArgumentException) {
            return new HttpError(400, 'Некорректные параметры запроса.');
        }

        if ($exception instanceof NewsNotFoundException) {
            return new HttpError(404, 'Новость не найдена.');
        }

        if ($exception instanceof PageOutOfRangeException) {
            return new HttpError(404, 'Страница не найдена.');
        }

        return new HttpError(500, 'Внутренняя ошибка сервера.');
    }
}
