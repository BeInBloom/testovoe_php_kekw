<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Security;

use App\Presentation\Security\OutputSanitizer;
use PHPUnit\Framework\TestCase;

final class OutputSanitizerTest extends TestCase {
    public function test_escape_html_special_chars(): void {
        $value = '\'"><script>alert(1)</script>';

        $escaped = OutputSanitizer::escape($value);

        $this->assertSame('&#039;&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;', $escaped);
    }

    public function test_sanitize_rich_text_keeps_allowed_tags_and_drops_attributes(): void {
        $value = '<p onclick="alert(1)">Hello<script>alert(2)</script><br class="x" /></p>';

        $sanitized = OutputSanitizer::sanitizeRichText($value);

        $this->assertSame('<p>Hello<br></p>', $sanitized);
    }

    public function test_sanitize_rich_text_removes_disallowed_tags(): void {
        $value = '<div>Text <strong>bold</strong> <p>inside</p></div>';

        $sanitized = OutputSanitizer::sanitizeRichText($value);

        $this->assertSame('Text bold <p>inside</p>', $sanitized);
    }

    public function test_sanitize_image_name_returns_basename(): void {
        $safeName = OutputSanitizer::sanitizeImageName('../images/photo.jpg');

        $this->assertSame('photo.jpg', $safeName);
    }

    public function test_sanitize_image_name_returns_empty_for_invalid_name(): void {
        $unsafeName = OutputSanitizer::sanitizeImageName('evil.jpg" onerror="alert(1)');

        $this->assertSame('', $unsafeName);
    }

    public function test_plain_text_removes_tags_and_normalizes_spaces(): void {
        $value = "<p>Text</p>\n\t<span> more   text</span><script>alert(1)</script>";

        $plain = OutputSanitizer::plainText($value);

        $this->assertSame('Text more text', $plain);
    }

    public function test_resolve_public_image_path_returns_existing_public_image(): void {
        $path = OutputSanitizer::resolvePublicImagePath('954e644b21f5340bd90c930e0173a425.jpg');

        $this->assertSame('/images/954e644b21f5340bd90c930e0173a425.jpg', $path);
    }

    public function test_resolve_public_image_path_returns_null_for_missing_file(): void {
        $path = OutputSanitizer::resolvePublicImagePath('missing-image.jpg');

        $this->assertNull($path);
    }

    public function test_format_date_returns_day_month_year(): void {
        $formatted = OutputSanitizer::formatDate('2412-05-26 00:00:00');

        $this->assertSame('26.05.2412', $formatted);
    }

    public function test_format_date_falls_back_to_plain_text_for_invalid_value(): void {
        $formatted = OutputSanitizer::formatDate('<b>invalid</b>');

        $this->assertSame('invalid', $formatted);
    }
}
