<?php

declare(strict_types=1);

$jobs = [
    [
        'src' => __DIR__.'/../public/images/content/home/mission/mission.jpg',
        'dst' => __DIR__.'/../public/images/content/about/story.jpg',
        'width' => 1400,
        'quality' => 78,
    ],
    [
        'src' => __DIR__.'/../public/images/content/about/archive/hero.jpg',
        'dst' => __DIR__.'/../public/images/content/about/identity.jpg',
        'width' => 1200,
        'quality' => 80,
    ],
    [
        'src' => __DIR__.'/../public/images/content/products/certidocs.jpg',
        'dst' => __DIR__.'/../public/images/content/products/identity.jpg',
        'width' => 1200,
        'quality' => 80,
    ],
    [
        'src' => __DIR__.'/../public/images/content/products/archive/network-source.jpg',
        'dst' => __DIR__.'/../public/images/content/products/network.jpg',
        'width' => 1400,
        'quality' => 78,
    ],
];

foreach ($jobs as $job) {
    $info = getimagesize($job['src']);
    if ($info === false) {
        fwrite(STDERR, "Failed to probe {$job['src']}\n");
        exit(1);
    }

    $src = match ($info[2]) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($job['src']),
        IMAGETYPE_PNG => imagecreatefrompng($job['src']),
        IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($job['src']) : false,
        default => false,
    };

    if ($src === false) {
        fwrite(STDERR, "Failed to read {$job['src']}\n");
        exit(1);
    }

    $w = imagesx($src);
    $h = imagesy($src);
    $tw = min($job['width'], $w);
    $th = (int) round($h * ($tw / $w));
    $dst = imagecreatetruecolor($tw, $th);
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefilledrectangle($dst, 0, 0, $tw, $th, $white);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
    imagejpeg($dst, $job['dst'], $job['quality']);
    imagedestroy($src);
    imagedestroy($dst);

    $kb = round(filesize($job['dst']) / 1024, 1);
    echo basename($job['dst'])." {$kb}KB (from {$w}x{$h})\n";
}
