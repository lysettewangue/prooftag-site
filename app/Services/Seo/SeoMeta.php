<?php

declare(strict_types=1);

namespace App\Services\Seo;

final readonly class SeoMeta
{
    /**
     * @param  array<string, string>  $hreflang
     */
    public function __construct(
        public string $title,
        public ?string $description,
        public string $canonical,
        public array $hreflang,
    ) {}
}
