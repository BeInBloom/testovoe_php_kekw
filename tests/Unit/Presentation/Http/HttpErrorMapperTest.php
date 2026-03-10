<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Http;

use App\Domain\Exceptions\NewsNotFoundException;
use App\Domain\Exceptions\PageOutOfRangeException;
use App\Presentation\Http\HttpErrorMapper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class HttpErrorMapperTest extends TestCase {
    public function test_map_invalid_argument_exception_to_bad_request(): void {
        $mapper = new HttpErrorMapper();

        $error = $mapper->map(new InvalidArgumentException('Bad request'));

        $this->assertSame(400, $error->statusCode);
        $this->assertSame('Некорректные параметры запроса.', $error->clientMessage);
    }

    public function test_map_not_found_exception_to_not_found_status(): void {
        $mapper = new HttpErrorMapper();

        $error = $mapper->map(NewsNotFoundException::byId(123));

        $this->assertSame(404, $error->statusCode);
        $this->assertSame('Новость не найдена.', $error->clientMessage);
    }

    public function test_map_page_out_of_range_exception_to_not_found_status(): void {
        $mapper = new HttpErrorMapper();

        $error = $mapper->map(PageOutOfRangeException::fromPage(5, 3));

        $this->assertSame(404, $error->statusCode);
        $this->assertSame('Страница не найдена.', $error->clientMessage);
    }

    public function test_map_runtime_exception_to_internal_server_error(): void {
        $mapper = new HttpErrorMapper();

        $error = $mapper->map(new RuntimeException('Unexpected'));

        $this->assertSame(500, $error->statusCode);
        $this->assertSame('Внутренняя ошибка сервера.', $error->clientMessage);
    }
}
