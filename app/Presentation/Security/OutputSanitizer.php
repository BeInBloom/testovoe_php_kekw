<?php

declare(strict_types=1);

namespace App\Presentation\Security;

use DateTimeImmutable;

final class OutputSanitizer {
    private const int HTML_ESCAPE_FLAGS = ENT_QUOTES | ENT_SUBSTITUTE;
    private const string HTML_ENCODING  = 'UTF-8';
    private const string IMAGES_DIR     = '/public/images/';

    public static function escape(string $value): string {
        return htmlspecialchars($value, self::HTML_ESCAPE_FLAGS, self::HTML_ENCODING);
    }

    public static function sanitizeRichText(string $value): string {
        $sanitized = strip_tags(self::removeDangerousBlocks($value), '<p><br>');

        $withoutParagraphAttributes = preg_replace('/<p\b[^>]*>/i', '<p>', $sanitized);
        if (!is_string($withoutParagraphAttributes)) {
            return '';
        }

        $withoutBreakAttributes = preg_replace('/<br\b[^>]*\/?>/i', '<br>', $withoutParagraphAttributes);
        if (!is_string($withoutBreakAttributes)) {
            return '';
        }

        return $withoutBreakAttributes;
    }

    public static function sanitizeImageName(string $imageName): string {
        $imageName = basename($imageName);

        if ($imageName === '' || preg_match('/^[a-zA-Z0-9._-]+$/', $imageName) !== 1) {
            return '';
        }

        return $imageName;
    }

    public static function resolvePublicImagePath(string $imageName): ?string {
        $safeName = self::sanitizeImageName($imageName);

        if ($safeName === '') {
            return null;
        }

        $projectRoot = dirname(__DIR__, 3);
        $fullPath    = $projectRoot . self::IMAGES_DIR . $safeName;

        if (!is_file($fullPath)) {
            return null;
        }

        return '/images/' . $safeName;
    }

    public static function plainText(string $value): string {
        $withoutTags  = strip_tags(self::removeDangerousBlocks($value));
        $singleSpaced = preg_replace('/\s+/u', ' ', $withoutTags);

        return trim($singleSpaced ?? '');
    }

    public static function formatDate(string $value): string {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);

        if ($date === false) {
            return self::plainText($value);
        }

        return $date->format('d.m.Y');
    }

    private static function removeDangerousBlocks(string $value): string {
        $sanitized = preg_replace('/<(script|style)\b[^>]*>.*?<\/\\1>/is', '', $value);

        return is_string($sanitized) ? $sanitized : '';
    }
}
