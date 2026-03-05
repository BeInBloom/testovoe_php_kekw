<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Http;

use App\Domain\Exceptions\NewsNotFoundException;
use App\Presentation\Http\HttpErrorMapper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class HttpErrorMapperTest extends TestCase {
    public function test_map_invalid_argument_exception_to_bad_request(): void {
        $mapper = new HttpErrorMapper();

        $error = $mapper->map(new InvalidArgumentException('Bad request'));

        $this->assertSame(400, $error->statusCode);
        $this->assertSame('Invalid request parameters.', $error->clientMessage);
    }

    public function test_map_not_found_exception_to_not_found_status(): void {
        $mapper = new HttpErrorMapper();

        $error = $mapper->map(NewsNotFoundException::byId(123));

        $this->assertSame(404, $error->statusCode);
        $this->assertSame('News not found.', $error->clientMessage);
    }

    public function test_map_runtime_exception_to_internal_server_error(): void {
        $mapper = new HttpErrorMapper();

        $error = $mapper->map(new RuntimeException('Unexpected'));

        $this->assertSame(500, $error->statusCode);
        $this->assertSame('Internal server error.', $error->clientMessage);
    }
}
