<?php

declare(strict_types=1);

namespace Tests\Integration\Smoke;

use PHPUnit\Framework\TestCase;

final class OpsConfigSmokeTest extends TestCase {
    public function test_dockerignore_excludes_env_and_logs(): void {
        $dockerIgnore = file_get_contents($this->projectPath('.dockerignore'));

        self::assertNotFalse($dockerIgnore);
        self::assertStringContainsString('.env', $dockerIgnore);
        self::assertStringContainsString('storage/logs', $dockerIgnore);
    }

    public function test_dockerfile_does_not_use_world_writable_log_directory(): void {
        $dockerfile = file_get_contents($this->projectPath('docker/php/Dockerfile'));

        self::assertNotFalse($dockerfile);
        self::assertStringNotContainsString('chmod 777', $dockerfile);
        self::assertStringContainsString('chmod 755 /var/log/app', $dockerfile);
    }

    public function test_nginx_denies_access_to_sensitive_files(): void {
        $nginxConfig = file_get_contents($this->projectPath('docker/nginx/default.conf'));

        self::assertNotFalse($nginxConfig);
        self::assertStringContainsString('composer\\.(json|lock)', $nginxConfig);
        self::assertStringContainsString('location ~ /\\.(?!well-known)', $nginxConfig);
    }

    private function projectPath(string $relativePath): string {
        return dirname(__DIR__, 3) . '/' . $relativePath;
    }
}
