<?php

declare(strict_types=1);

namespace Tests\Unit\Presentation\Security;

use App\Presentation\Security\OutputSanitizer;
use PHPUnit\Framework\TestCase;

final class OutputSanitizerTest extends TestCase
{
    public function test_escape_html_special_chars(): void
    {
        $value = '\'"><script>alert(1)</script>';

        $escaped = OutputSanitizer::escape($value);

        $this->assertSame('&#039;&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;', $escaped);
    }

    public function test_sanitize_rich_text_keeps_allowed_tags_and_drops_attributes(): void
    {
        $value = '<p onclick="alert(1)">Hello<script>alert(2)</script><br class="x" /></p>';

        $sanitized = OutputSanitizer::sanitizeRichText($value);

        $this->assertSame('<p>Helloalert(2)<br></p>', $sanitized);
    }

    public function test_sanitize_rich_text_removes_disallowed_tags(): void
    {
        $value = '<div>Text <strong>bold</strong> <p>inside</p></div>';

        $sanitized = OutputSanitizer::sanitizeRichText($value);

        $this->assertSame('Text bold <p>inside</p>', $sanitized);
    }

    public function test_sanitize_image_name_returns_basename(): void
    {
        $safeName = OutputSanitizer::sanitizeImageName('../images/photo.jpg');

        $this->assertSame('photo.jpg', $safeName);
    }

    public function test_sanitize_image_name_returns_empty_for_invalid_name(): void
    {
        $unsafeName = OutputSanitizer::sanitizeImageName('evil.jpg" onerror="alert(1)');

        $this->assertSame('', $unsafeName);
    }
}
