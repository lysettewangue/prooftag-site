@props(['hreflang' => []])

@php
    $locale = app()->getLocale();
    $navigationItems = \App\Support\PublicNavigation::items($locale, request()->path());
    $contactUrl = collect($navigationItems)->firstWhere('key', 'contact')['url'] ?? locale_route('home');
    $mainItems = collect($navigationItems)->values();
    $localeLinks = \App\Support\LocaleSwitcher::links(is_array($hreflang) ? $hreflang : []);
@endphp

<header class="public-site-header sticky top-0 z-40" data-public-shell-region="header" data-public-header>
    <div class="public-main-nav">
        <div class="public-main-nav__inner">
            <a href="{{ locale_route('home') }}" class="public-main-nav__logo" aria-label="{{ __('ui.brand_home') }}">
                <img src="{{ asset('images/brand/logo.png') }}" width="250" height="72" alt="{{ config('app.name') }}">
            </a>

            <span class="public-main-nav__divider" aria-hidden="true"></span>

            <nav class="public-main-nav__desktop min-w-0 self-stretch" aria-label="{{ __('ui.navigation.primary') }}">
                <ul class="flex h-full items-stretch">
                    @foreach ($mainItems as $item)
                        <li class="flex">
                            <a
                                href="{{ $item['url'] }}"
                                data-primary-navigation-item="{{ $item['key'] }}"
                                @class(['public-main-nav__link', 'is-active' => $item['active']])
                                @if ($item['active']) aria-current="page" @endif
                            >{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="public-main-nav__actions">
                <nav class="public-main-nav__languages" aria-label="{{ __('ui.language_navigation') }}" data-locale-switcher>
                    <a href="{{ $localeLinks['fr']['url'] }}" hreflang="fr" lang="fr" @class(['is-active' => $locale === 'fr']) @if ($locale === 'fr') aria-current="page" @endif>FR</a>
                    <span>|</span>
                    <a href="{{ $localeLinks['en']['url'] }}" hreflang="en" lang="en" @class(['is-active' => $locale === 'en']) @if ($locale === 'en') aria-current="page" @endif>EN</a>
                </nav>

                <a href="{{ $contactUrl }}" data-header-contact-cta class="public-main-nav__cta">
                    {{ __('ui.header.cta') }} <x-public.icon name="arrow-right" :size="15" />
                </a>

                <details class="public-main-nav__mobile group relative" data-public-mobile-nav>
                    <summary class="public-main-nav__menu" aria-label="{{ __('ui.navigation.menu') }}">
                        <span class="group-open:hidden"><x-public.icon name="menu" :size="20" /></span>
                        <span class="hidden group-open:inline"><x-public.icon name="x" :size="20" /></span>
                    </summary>
                    <div class="absolute top-[calc(100%+0.75rem)] right-0 z-50 w-[min(21rem,calc(100vw-2rem))] overflow-hidden rounded-xl border border-line bg-white p-2 shadow-card">
                        <nav aria-label="{{ __('ui.navigation.mobile') }}">
                            <ul class="grid gap-0.5">
                                @foreach ($navigationItems as $item)
                                    <li><a href="{{ $item['url'] }}" data-primary-navigation-item="{{ $item['key'] }}" @class(['flex min-h-11 items-center rounded-lg px-3 text-sm font-semibold', 'bg-brand-cyan-100 text-brand-azure-700' => $item['active'], 'text-ink hover:bg-surface-subtle' => ! $item['active']])>{{ $item['label'] }}</a></li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                </details>
            </div>
        </div>
    </div>
</header>
