<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\HtmlString;
use InvalidArgumentException;

/**
 * Renders curated Lucide SVG icons for the public site (step 128).
 */
final class LucideIcon
{
    /**
     * @param  array<string, scalar|null>  $attributes
     */
    public static function svg(string $name, array $attributes = []): HtmlString
    {
        $name = self::assertName($name);
        $path = resource_path('svg/lucide/'.$name.'.svg');

        if (! File::isFile($path)) {
            throw new InvalidArgumentException("Unknown Lucide icon [{$name}].");
        }

        $svg = trim((string) preg_replace('/<!--.*?-->/s', '', (string) File::get($path)));

        if (! preg_match('/^<svg\b([^>]*)>([\s\S]*)<\/svg>$/', $svg, $matches)) {
            throw new InvalidArgumentException("Lucide icon [{$name}] is not a valid SVG document.");
        }

        $defaults = [
            'xmlns' => 'http://www.w3.org/2000/svg',
            'width' => '24',
            'height' => '24',
            'viewBox' => '0 0 24 24',
            'fill' => 'none',
            'stroke' => 'currentColor',
            'stroke-width' => '1.75',
            'stroke-linecap' => 'round',
            'stroke-linejoin' => 'round',
            'class' => 'lucide lucide-'.$name,
            'aria-hidden' => 'true',
            'focusable' => 'false',
        ];

        $merged = array_merge($defaults, $attributes);

        if (($merged['aria-hidden'] ?? null) === false || ($merged['aria-hidden'] ?? null) === 'false') {
            unset($merged['aria-hidden']);
        }

        $attributeString = collect($merged)
            ->filter(static fn (mixed $value): bool => $value !== null && $value !== false)
            ->map(static function (mixed $value, string $key): string {
                if ($value === true) {
                    return $key;
                }

                return $key.'="'.e((string) $value).'"';
            })
            ->implode(' ');

        return new HtmlString('<svg '.$attributeString.'>'.$matches[2].'</svg>');
    }

    public static function exists(string $name): bool
    {
        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $name)) {
            return false;
        }

        return File::isFile(resource_path('svg/lucide/'.$name.'.svg'));
    }

    /**
     * @return list<string>
     */
    public static function names(): array
    {
        $manifest = resource_path('svg/lucide/manifest.json');

        if (! File::isFile($manifest)) {
            return [];
        }

        /** @var array{icons?: list<string>} $payload */
        $payload = json_decode((string) File::get($manifest), true, 512, JSON_THROW_ON_ERROR);

        return array_values($payload['icons'] ?? []);
    }

    private static function assertName(string $name): string
    {
        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $name)) {
            throw new InvalidArgumentException('Lucide icon names must be lowercase kebab-case.');
        }

        return $name;
    }
}
