<?php

declare(strict_types=1);

$root = dirname(__DIR__).DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'content';
$minimumBytes = 250 * 1024;
$quality = 82;
$converted = 0;
$savedBytes = 0;

if (! is_dir($root) || ! function_exists('imagewebp')) {
    fwrite(STDERR, "The content image directory or WebP support is unavailable.\n");
    exit(1);
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
);

foreach ($iterator as $file) {
    if (! $file instanceof SplFileInfo || ! $file->isFile() || $file->getSize() < $minimumBytes) {
        continue;
    }

    $extension = strtolower($file->getExtension());

    if (! in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
        continue;
    }

    $source = $file->getPathname();
    $target = substr($source, 0, -strlen($extension)).'webp';

    if (is_file($target) && filemtime($target) >= filemtime($source)) {
        continue;
    }

    $image = match ($extension) {
        'jpg', 'jpeg' => @imagecreatefromjpeg($source),
        'png' => @imagecreatefrompng($source),
    };

    if (! $image instanceof GdImage) {
        fwrite(STDERR, "Skipped unreadable image: {$source}\n");

        continue;
    }

    if ($extension === 'png') {
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
    }

    $temporary = $target.'.tmp';
    $written = imagewebp($image, $temporary, $quality);
    imagedestroy($image);

    if (! $written || ! is_file($temporary)) {
        @unlink($temporary);

        continue;
    }

    $sourceBytes = filesize($source) ?: 0;
    $targetBytes = filesize($temporary) ?: 0;

    if ($targetBytes <= 0 || $targetBytes >= $sourceBytes) {
        unlink($temporary);

        continue;
    }

    if (is_file($target)) {
        unlink($target);
    }

    rename($temporary, $target);
    touch($target, filemtime($source) ?: time());
    $converted++;
    $savedBytes += $sourceBytes - $targetBytes;

    $relative = str_replace(dirname(__DIR__).DIRECTORY_SEPARATOR, '', $target);
    printf("%-95s %7.1f KB -> %7.1f KB\n", $relative, $sourceBytes / 1024, $targetBytes / 1024);
}

printf("Converted %d images; saved %.2f MB.\n", $converted, $savedBytes / 1024 / 1024);
