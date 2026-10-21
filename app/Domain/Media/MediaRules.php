<?php

declare(strict_types=1);

namespace App\Domain\Media;

final class MediaRules
{
    /**
     * @return list<string>
     */
    public static function allowedMimes(): array
    {
        /** @var list<string> $mimes */
        $mimes = config('cms.media.allowed_mimes', []);

        return $mimes;
    }

    /**
     * @return list<string>
     */
    public static function imageMimes(): array
    {
        /** @var list<string> $mimes */
        $mimes = config('cms.media.image_mimes', []);

        return $mimes;
    }

    public static function maxBytes(): int
    {
        return (int) config('cms.media.max_bytes', 8 * 1024 * 1024);
    }

    public static function isImage(string $mime): bool
    {
        return in_array($mime, self::imageMimes(), true);
    }

    public static function isAllowed(string $mime): bool
    {
        return in_array($mime, self::allowedMimes(), true);
    }
}
