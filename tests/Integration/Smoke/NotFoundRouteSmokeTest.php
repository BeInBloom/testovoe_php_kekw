<?php

declare(strict_types=1);

namespace Tests\Integration\Smoke;

use PHPUnit\Framework\TestCase;

final class NotFoundRouteSmokeTest extends TestCase {
    public function test_unknown_route_renders_not_found_stub_with_404_status(): void {
        $originalRequestUri = $_SERVER['REQUEST_URI'] ?? null;
        $originalGet        = $_GET;

        $_SERVER['REQUEST_URI'] = '/missing-endpoint';
        $_GET                   = [];
        http_response_code(200);

        ob_start();
        include dirname(__DIR__, 3) . '/public/index.php';
        $output = ob_get_clean();

        if ($originalRequestUri === null) {
            unset($_SERVER['REQUEST_URI']);
        } else {
            $_SERVER['REQUEST_URI'] = $originalRequestUri;
        }
        $_GET = $originalGet;

        self::assertIsString($output);
        self::assertSame(404, http_response_code());
        self::assertStringContainsString('404 не найдено', $output);
    }
}
