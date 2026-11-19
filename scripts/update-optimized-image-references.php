<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$publicRoot = $projectRoot.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR;
$searchRoots = [
    $projectRoot.DIRECTORY_SEPARATOR.'app',
    $projectRoot.DIRECTORY_SEPARATOR.'lang',
    $projectRoot.DIRECTORY_SEPARATOR.'resources',
];
$allowedExtensions = ['php', 'css', 'js'];
$updatedFiles = 0;
$updatedReferences = 0;

foreach ($searchRoots as $searchRoot) {
    if (! is_dir($searchRoot)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($searchRoot, FilesystemIterator::SKIP_DOTS),
    );

    foreach ($iterator as $file) {
        if (! $file instanceof SplFileInfo || ! $file->isFile()) {
            continue;
        }

        if (! in_array(strtolower($file->getExtension()), $allowedExtensions, true)) {
            continue;
        }

        $path = $file->getPathname();
        $contents = file_get_contents($path);

        if ($contents === false || ! str_contains($contents, 'images/content/')) {
            continue;
        }

        $fileReplacements = 0;
        $updated = preg_replace_callback(
            '~images/content/[A-Za-z0-9_./-]+\.(?:png|jpe?g)~i',
            static function (array $matches) use ($publicRoot, &$fileReplacements): string {
                $webpPath = preg_replace('~\.(?:png|jpe?g)$~i', '.webp', $matches[0]);

                if (! is_string($webpPath)) {
                    return $matches[0];
                }

                $diskPath = $publicRoot.str_replace('/', DIRECTORY_SEPARATOR, $webpPath);

                if (! is_file($diskPath)) {
                    return $matches[0];
                }

                $fileReplacements++;

                return $webpPath;
            },
            $contents,
        );

        if (! is_string($updated) || $fileReplacements === 0 || $updated === $contents) {
            continue;
        }

        if (file_put_contents($path, $updated) === false) {
            fwrite(STDERR, "Unable to update {$path}.\n");
            exit(1);
        }

        $relative = str_replace($projectRoot.DIRECTORY_SEPARATOR, '', $path);
        printf("%-80s %3d reference(s)\n", $relative, $fileReplacements);
        $updatedFiles++;
        $updatedReferences += $fileReplacements;
    }
}

printf("Updated %d image references across %d files.\n", $updatedReferences, $updatedFiles);
