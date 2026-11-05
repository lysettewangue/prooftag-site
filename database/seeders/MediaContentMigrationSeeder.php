<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProductCode;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\HasMedia;

/**
 * Step 159 — pull, classify, optimise, and attach retained media.
 *
 * Full WP media list: docs/legacy/archive/2026-10-20/site-media-p1.json
 * Classification: docs/content/media-classification.csv
 * DITROS marks never attach to corporate chrome or CERTIDOCS CT.
 */
class MediaContentMigrationSeeder extends Seeder
{
    private const ARCHIVE_MEDIA = 'docs/legacy/archive/2026-10-20/media';

    private const BINARIES = 'docs/legacy/archive/2026-10-20/media/binaries';

    public function run(): void
    {
        $this->syncBrandStaticAssets();
        $this->attachSiteLogo();
        $this->attachProductMark(
            ProductCode::CtVerif,
            'CT-verif-logo-e1721923966316.png',
            [
                'fr' => 'Logo CT-VERIF',
                'en' => 'CT-VERIF logo',
            ],
            'PROOFTAG-CATIS — CT-VERIF product mark; public use after VER-04',
        );
        $this->attachProductMark(
            ProductCode::Doser,
            'Image1.png',
            [
                'fr' => 'Logo DOSER',
                'en' => 'DOSER logo',
            ],
            'PROOFTAG-CATIS — DOSER product mark; public use after VER-05',
        );
        $this->optimiseArchiveOversized();

        ContentCache::flush();
    }

    private function syncBrandStaticAssets(): void
    {
        $map = [
            'Logo_Prooftag_CATIS_SA_Redesigne_Ok-removebg.png' => public_path('images/brand/logo.png'),
            'Logo_Prooftag_CATIS_SA_Redesigne_Ok-white-e1719944621102.png' => public_path('images/brand/logo-white.png'),
            'Favicone.png' => public_path('images/brand/favicon.png'),
        ];

        foreach ($map as $sourceName => $dest) {
            $source = $this->resolveSourcePath($sourceName);
            $dir = dirname($dest);
            if (! is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            if (! copy($source, $dest)) {
                throw new \RuntimeException("Failed to copy brand asset {$sourceName} → {$dest}");
            }
        }
    }

    private function attachSiteLogo(): void
    {
        $settings = SiteSetting::query()->find(1);

        if ($settings === null) {
            throw new \RuntimeException('SiteSetting id=1 missing; run SiteSettingSeeder first.');
        }

        $this->attachImage(
            $settings,
            'Logo_Prooftag_CATIS_SA_Redesigne_Ok-removebg.png',
            'logo-prooftag-catis.png',
            [
                'fr' => 'Logo PROOFTAG-CATIS',
                'en' => 'PROOFTAG-CATIS logo',
            ],
            'PROOFTAG-CATIS — official corporate logo (AUD-028)',
        );
    }

    /**
     * @param  array{fr: string, en: string}  $alt
     */
    private function attachProductMark(ProductCode $code, string $sourceName, array $alt, string $rights): void
    {
        $product = Product::query()->where('code', $code)->first();

        if ($product === null) {
            throw new \RuntimeException("Product {$code->value} missing; run ProductSeeder first.");
        }

        $this->attachImage(
            $product,
            $sourceName,
            $this->safeFileName($sourceName),
            $alt,
            $rights,
        );
    }

    /**
     * @param  array{fr: string, en: string}  $alt
     */
    private function attachImage(
        HasMedia&Model $owner,
        string $sourceName,
        string $fileName,
        array $alt,
        string $rights,
    ): void {
        $source = $this->resolveSourcePath($sourceName);
        $optimised = $this->ensureUnderLimit($source, $fileName);

        $owner->clearMediaCollection('images');

        $owner
            ->addMedia($optimised)
            ->preservingOriginal()
            ->usingFileName($fileName)
            ->withCustomProperties([
                'alt.fr' => $alt['fr'],
                'alt.en' => $alt['en'],
                'rights' => $rights,
            ])
            ->toMediaCollection('images');

        $owner->unsetRelation('media');
    }

    private function resolveSourcePath(string $fileName): string
    {
        $candidates = [
            base_path(self::ARCHIVE_MEDIA.'/'.$fileName),
            base_path(self::BINARIES.'/'.$fileName),
            database_path('seeders/fixtures/media/'.$fileName),
        ];

        foreach ($candidates as $path) {
            if (is_file($path) && filesize($path) > 0) {
                return $path;
            }
        }

        throw new \RuntimeException("Missing media binary for {$fileName}. Run the step-159 binary pull into ".self::BINARIES);
    }

    private function ensureUnderLimit(string $source, string $preferredName): string
    {
        $max = (int) config('cms.media.max_bytes', 8 * 1024 * 1024);
        $size = filesize($source);

        if ($size !== false && $size <= $max) {
            return $source;
        }

        $fixtureDir = database_path('seeders/fixtures/media');
        if (! is_dir($fixtureDir)) {
            mkdir($fixtureDir, 0777, true);
        }

        $dest = $fixtureDir.DIRECTORY_SEPARATOR.pathinfo($preferredName, PATHINFO_FILENAME).'-optimised.jpg';

        $this->resizeToJpeg($source, $dest, 1600, 82);

        if (! is_file($dest) || filesize($dest) > $max) {
            throw new \RuntimeException("Could not optimise {$source} under {$max} bytes.");
        }

        return $dest;
    }

    private function optimiseArchiveOversized(): void
    {
        $fixtureDir = database_path('seeders/fixtures/media');
        if (! is_dir($fixtureDir)) {
            mkdir($fixtureDir, 0777, true);
        }

        foreach (['PV.png', 'team.png'] as $name) {
            $source = base_path(self::BINARIES.'/'.$name);
            if (! is_file($source)) {
                continue;
            }

            $dest = $fixtureDir.DIRECTORY_SEPARATOR.pathinfo($name, PATHINFO_FILENAME).'-archive-optimised.jpg';
            if (is_file($dest) && filesize($dest) > 0) {
                continue;
            }

            try {
                $this->resizeToJpeg($source, $dest, 1200, 75);
            } catch (\Throwable $e) {
                $this->command?->warn("Skip archive optimise for {$name}: ".$e->getMessage());
            }
        }
    }

    private function resizeToJpeg(string $source, string $dest, int $maxEdge, int $quality): void
    {
        $info = @getimagesize($source);
        if ($info === false) {
            throw new \RuntimeException("Unreadable image: {$source}");
        }

        [$width, $height, $type] = $info;

        // Rough RGBA decode budget — skip pathological legacy PNGs on low-memory hosts.
        if (($width * $height) > 25_000_000) {
            throw new \RuntimeException("Image too large for GD decode ({$width}×{$height}).");
        }

        $previous = ini_get('memory_limit');
        ini_set('memory_limit', '1024M');

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG => @imagecreatefrompng($source),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($source) : false,
            default => false,
        };

        if ($previous !== false) {
            ini_set('memory_limit', $previous);
        }

        if ($src === false) {
            throw new \RuntimeException("GD cannot open: {$source}");
        }

        $scale = min(1.0, $maxEdge / max($width, $height));
        $newW = max(1, (int) round($width * $scale));
        $newH = max(1, (int) round($height * $scale));

        $dst = imagecreatetruecolor($newW, $newH);
        if ($dst === false) {
            imagedestroy($src);
            throw new \RuntimeException('GD truecolor alloc failed.');
        }

        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $newW, $newH, $white);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);

        if (! imagejpeg($dst, $dest, $quality)) {
            imagedestroy($src);
            imagedestroy($dst);
            throw new \RuntimeException("Failed writing {$dest}");
        }

        imagedestroy($src);
        imagedestroy($dst);
    }

    private function safeFileName(string $name): string
    {
        $base = strtolower(pathinfo($name, PATHINFO_FILENAME));
        $base = preg_replace('/[^a-z0-9]+/', '-', $base) ?? 'media';
        $base = trim($base, '-');
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        return ($base !== '' ? $base : 'media').($ext !== '' ? '.'.$ext : '');
    }
}
