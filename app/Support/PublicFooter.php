<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Locale;
use App\Enums\PageCode;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

final class PublicFooter
{
    private static ?bool $siteSettingsTableExists = null;

    /**
     * @return array{
     *     legal_name: string,
     *     brand: string,
     *     tagline: string,
     *     building: string|null,
     *     postal_line: string|null,
     *     telephone: string|null,
     *     telephone_uri: string|null,
     *     email: string|null,
     *     useful_links: list<array{label: string, url: string}>,
     *     legal_links: list<array{label: string, url: string}>,
     *     social_links: list<array{label: string, url: string, icon: string}>
     * }
     */
    public function forLocale(string $locale): array
    {
        $locale = $locale === Locale::English->value ? Locale::English->value : Locale::French->value;
        $settings = $this->siteSettingsTableExists()
            ? SiteSetting::query()->with(['translations', 'media'])->find(1)
            : null;
        $translation = $settings?->translationFor($locale);
        $navigation = PublicNavigation::items($locale);

        return [
            'legal_name' => $settings?->legal_name ?: (string) config('app.name'),
            'brand' => $settings?->brand ?: (string) config('app.name'),
            'tagline' => $translation?->tagline ?: trans('ui.footer.baseline'),
            'building' => $settings?->building,
            'postal_line' => $settings instanceof SiteSetting ? $this->postalLine($settings, $locale) : null,
            'telephone' => $settings?->telephone,
            'telephone_uri' => $settings instanceof SiteSetting ? $this->telephoneUri((string) $settings->telephone) : null,
            'email' => $settings?->email,
            'useful_links' => $this->linksForKeys($navigation, [
                'about',
                'technical_inspection',
                'centres',
                'contact',
            ]),
            'legal_links' => $this->legalLinks($locale),
            'social_links' => $settings instanceof SiteSetting ? $this->socialLinks($settings) : [],
        ];
    }

    private function postalLine(SiteSetting $settings, string $locale): string
    {
        $postalBox = (string) $settings->postal_box;

        if ($locale === Locale::English->value && preg_match('/(\d.*)$/u', $postalBox, $matches) === 1) {
            $postalBox = trans('ui.footer.postal_box', ['number' => $matches[1]]);
        }

        $country = $locale === Locale::English->value
            ? trans('ui.footer.country')
            : (string) $settings->country;

        return trim($postalBox.' '.(string) $settings->city.', '.$country);
    }

    private function telephoneUri(string $telephone): ?string
    {
        $uri = preg_replace('/(?!^\+)[^0-9]/', '', $telephone);

        return is_string($uri) && $uri !== '' ? 'tel:'.$uri : null;
    }

    private function siteSettingsTableExists(): bool
    {
        return self::$siteSettingsTableExists ??= Schema::hasTable('site_settings');
    }

    /**
     * @param  list<array{key: string, label: string, url: string, active: bool}>  $navigation
     * @param  list<string>  $keys
     * @return list<array{label: string, url: string}>
     */
    private function linksForKeys(array $navigation, array $keys): array
    {
        $byKey = [];

        foreach ($navigation as $item) {
            $byKey[$item['key']] = ['label' => $item['label'], 'url' => $item['url']];
        }

        $links = [];

        foreach ($keys as $key) {
            if (isset($byKey[$key])) {
                $links[] = $byKey[$key];
            }
        }

        return $links;
    }

    /**
     * @return list<array{label: string, url: string}>
     */
    private function legalLinks(string $locale): array
    {
        $legalSlugs = PageCode::LegalNotice->slugs();
        $privacySlugs = PageCode::Privacy->slugs();

        return [
            [
                'label' => trans('ui.footer.legal_notice'),
                'url' => PublicPath::localized($locale, $legalSlugs[$locale]),
            ],
            [
                'label' => trans('ui.footer.privacy'),
                'url' => PublicPath::localized($locale, $privacySlugs[$locale]),
            ],
        ];
    }

    /**
     * @return list<array{label: string, url: string, icon: string}>
     */
    private function socialLinks(SiteSetting $settings): array
    {
        $links = [];

        foreach ([
            'social_linkedin' => ['label' => 'LinkedIn', 'icon' => 'linkedin'],
            'social_facebook' => ['label' => 'Facebook', 'icon' => 'facebook'],
            'social_x' => ['label' => 'X', 'icon' => 'x'],
            'social_youtube' => ['label' => 'YouTube', 'icon' => 'youtube'],
            'social_instagram' => ['label' => 'Instagram', 'icon' => 'instagram'],
        ] as $field => $meta) {
            $url = $settings->{$field};
            $scheme = is_string($url) ? parse_url($url, PHP_URL_SCHEME) : null;

            if (
                is_string($url)
                && filter_var($url, FILTER_VALIDATE_URL) !== false
                && in_array($scheme, ['http', 'https'], true)
            ) {
                $links[] = [
                    'label' => $meta['label'],
                    'url' => $url,
                    'icon' => $meta['icon'],
                ];
            }
        }

        return $links;
    }
}
