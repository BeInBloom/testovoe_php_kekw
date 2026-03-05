<?php

declare(strict_types=1);

namespace Tests\Integration\Smoke;

use PHPUnit\Framework\TestCase;

final class HealthEndpointSmokeTest extends TestCase {
    public function test_health_endpoint_returns_ok_json_payload(): void {
        ob_start();
        include dirname(__DIR__, 3) . '/public/health.php';
        $output = ob_get_clean();

        self::assertIsString($output);
        $decoded = json_decode($output, true);

        self::assertIsArray($decoded);
        self::assertSame('ok', $decoded['status'] ?? null);
        self::assertArrayHasKey('timestamp', $decoded);
    }
}
