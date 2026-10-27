<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Locale;
use App\Enums\PageCode;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

final class PublicFooter
{
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
     *     explorer_links: list<array{label: string, url: string}>,
     *     information_links: list<array{label: string, url: string}>,
     *     social_links: list<array{label: string, url: string}>
     * }
     */
    public function forLocale(string $locale): array
    {
        $locale = $locale === Locale::English->value ? Locale::English->value : Locale::French->value;
        $settings = Schema::hasTable('site_settings')
            ? SiteSetting::query()->with('translations')->find(1)
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
            'explorer_links' => $this->linksForKeys($navigation, [
                'products',
                'expertise',
                'technical_inspection',
                'centres',
                'resources',
                'news',
            ]),
            'information_links' => $this->informationLinks($locale, $navigation),
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

    /**
     * @param  list<array{key: string, label: string, url: string, active: bool}>  $navigation
     * @param  list<string>  $keys
     * @return list<array{label: string, url: string}>
     */
    private function linksForKeys(array $navigation, array $keys): array
    {
        $links = [];

        foreach ($navigation as $item) {
            if (in_array($item['key'], $keys, true)) {
                $links[] = ['label' => $item['label'], 'url' => $item['url']];
            }
        }

        return $links;
    }

    /**
     * @param  list<array{key: string, label: string, url: string, active: bool}>  $navigation
     * @return list<array{label: string, url: string}>
     */
    private function informationLinks(string $locale, array $navigation): array
    {
        $links = $this->linksForKeys($navigation, ['about', 'contact']);
        $legalSlugs = PageCode::LegalNotice->slugs();
        $privacySlugs = PageCode::Privacy->slugs();

        $links[] = [
            'label' => trans('ui.footer.legal_notice'),
            'url' => PublicPath::localized($locale, $legalSlugs[$locale]),
        ];
        $links[] = [
            'label' => trans('ui.footer.privacy'),
            'url' => PublicPath::localized($locale, $privacySlugs[$locale]),
        ];

        return $links;
    }

    /**
     * @return list<array{label: string, url: string}>
     */
    private function socialLinks(SiteSetting $settings): array
    {
        $links = [];

        foreach ([
            'social_linkedin' => 'LinkedIn',
            'social_facebook' => 'Facebook',
            'social_x' => 'X',
            'social_youtube' => 'YouTube',
            'social_instagram' => 'Instagram',
        ] as $field => $label) {
            $url = $settings->{$field};

            $scheme = is_string($url) ? parse_url($url, PHP_URL_SCHEME) : null;

            if (
                is_string($url)
                && filter_var($url, FILTER_VALIDATE_URL) !== false
                && in_array($scheme, ['http', 'https'], true)
            ) {
                $links[] = ['label' => $label, 'url' => $url];
            }
        }

        return $links;
    }
}
