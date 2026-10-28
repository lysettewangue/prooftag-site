<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Locale;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

final class PublicHeader
{
    /**
     * @return array{
     *     telephone: string|null,
     *     telephone_uri: string|null,
     *     email: string|null,
     *     location: string
     * }
     */
    public function forLocale(string $locale): array
    {
        $locale = $locale === Locale::English->value ? Locale::English->value : Locale::French->value;
        $settings = Schema::hasTable('site_settings')
            ? SiteSetting::query()->find(1)
            : null;

        $telephone = $settings?->telephone;

        return [
            'telephone' => $telephone,
            'telephone_uri' => filled($telephone) ? $this->telephoneUri((string) $telephone) : null,
            'email' => $settings?->email,
            'location' => trans('ui.header.location'),
        ];
    }

    private function telephoneUri(string $telephone): ?string
    {
        $uri = preg_replace('/(?!^\+)[^0-9]/', '', $telephone);

        return filled($uri) ? 'tel:'.$uri : null;
    }
}
