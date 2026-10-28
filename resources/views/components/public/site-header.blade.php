@props([
    'hreflang' => [],
])

@php
    $locale = app()->getLocale();
    $navigationItems = \App\Support\PublicNavigation::items($locale, request()->path());
    $searchUrl = \App\Support\PublicNavigation::searchUrl($locale);
    $contactUrl = collect($navigationItems)->firstWhere('key', 'contact')['url'] ?? locale_route('home');
    $contactItems = collect($navigationItems)->reject(fn (array $item): bool => $item['key'] === 'contact')->values();
    $localeLinks = \App\Support\LocaleSwitcher::links(is_array($hreflang) ? $hreflang : []);
@endphp

<header
    class="public-site-header sticky top-0 z-40"
    data-public-shell-region="header"
    data-public-header
>
    {{-- Utility strip — G3-inspired: brand band, contact, locale --}}
    <div class="public-utility-strip">
        <div class="mx-auto flex min-h-[2.125rem] max-w-site items-center justify-between gap-3 px-gutter-compact text-white medium:px-gutter-medium">
            <div class="flex min-w-0 items-center gap-x-3 gap-y-1 text-[0.7rem] font-semibold tracking-wide">
                <span class="hidden truncate medium:inline">{{ $header['location'] }}</span>
                <span class="hidden text-white/35 wide:inline" aria-hidden="true">·</span>
                <span class="truncate text-white/90">{{ __('ui.header.utility') }}</span>
            </div>

            <div class="flex shrink-0 items-center gap-3">
                @if ($header['telephone'] && $header['telephone_uri'])
                    <a
                        href="{{ $header['telephone_uri'] }}"
                        class="hidden items-center gap-1.5 rounded-sm text-[0.7rem] font-bold tracking-wide text-white transition-colors hover:text-brand-cyan-100 medium:inline-flex"
                    >
                        <x-public.icon name="phone" :size="14" class="text-brand-cyan-500" />
                        <span>{{ $header['telephone'] }}</span>
                    </a>
                @endif

                <nav class="flex items-center gap-1" aria-label="{{ __('ui.language_navigation') }}" data-locale-switcher>
                    <a
                        href="{{ $localeLinks['fr']['url'] }}"
                        hreflang="fr"
                        lang="fr"
                        @class([
                            'inline-flex min-h-7 min-w-8 items-center justify-center rounded-md px-2 text-[0.68rem] font-bold transition-colors',
                            'bg-white text-brand-royal' => $locale === 'fr',
                            'text-white/80 hover:bg-white/10 hover:text-white' => $locale !== 'fr',
                        ])
                        @if ($locale === 'fr') aria-current="page" @endif
                        @if (! $localeLinks['fr']['available']) title="{{ __('ui.language_missing.fr') }}" @endif
                    >FR</a>
                    <span class="text-white/30" aria-hidden="true">|</span>
                    <a
                        href="{{ $localeLinks['en']['url'] }}"
                        hreflang="en"
                        lang="en"
                        @class([
                            'inline-flex min-h-7 min-w-8 items-center justify-center rounded-md px-2 text-[0.68rem] font-bold transition-colors',
                            'bg-white text-brand-royal' => $locale === 'en',
                            'text-white/80 hover:bg-white/10 hover:text-white' => $locale !== 'en',
                        ])
                        @if ($locale === 'en') aria-current="page" @endif
                        @if (! $localeLinks['en']['available']) title="{{ __('ui.language_missing.en') }}" @endif
                    >EN</a>
                </nav>
            </div>
        </div>
    </div>

    {{-- Primary bar — white shell, logo, nav, actions --}}
    <div class="border-b border-line/80 bg-white/95 shadow-soft backdrop-blur-md">
        <div class="mx-auto flex max-w-site items-center gap-4 px-gutter-compact medium:px-gutter-medium">
            <a
                href="{{ locale_route('home') }}"
                class="inline-flex min-h-[4.25rem] shrink-0 items-center py-2"
                aria-label="{{ __('ui.brand_home') }}"
            >
                <img
                    src="{{ asset('images/brand/logo.png') }}"
                    width="250"
                    height="72"
                    alt="{{ config('app.name') }}"
                    class="h-10 w-auto medium:h-11"
                >
            </a>

            <nav class="ml-2 hidden min-w-0 flex-1 items-stretch self-stretch wide:flex" aria-label="{{ __('ui.navigation.primary') }}">
                <ul class="flex w-full items-stretch justify-end gap-1">
                    @foreach ($contactItems as $item)
                        <li class="flex">
                            <a
                                href="{{ $item['url'] }}"
                                data-primary-navigation-item="{{ $item['key'] }}"
                                @class([
                                    'relative flex items-center px-2 text-[0.8rem] font-bold tracking-tight transition-colors nav:px-2.5 nav:text-[0.8125rem]',
                                    'text-brand-royal after:absolute after:inset-x-2 after:bottom-0 after:h-0.5 after:rounded-pill after:bg-brand-cyan-500' => $item['active'],
                                    'text-brand-azure-600/90 hover:text-brand-royal' => ! $item['active'],
                                ])
                                @if ($item['active']) aria-current="page" @endif
                            >{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="ml-auto flex shrink-0 items-center gap-2">
                <a
                    href="{{ $searchUrl }}"
                    class="inline-flex size-10 items-center justify-center rounded-control border border-line text-brand-royal transition-colors hover:border-brand-royal/30 hover:bg-surface-subtle"
                    aria-label="{{ __('ui.navigation.search') }}"
                >
                    <x-public.icon name="search" :size="18" />
                </a>

                <a
                    href="{{ $contactUrl }}"
                    data-primary-navigation-item="contact"
                    class="hidden min-h-10 items-center rounded-control bg-brand-royal px-4 text-[0.8125rem] font-bold text-white shadow-[0_10px_24px_rgb(14_102_151_/_0.22)] transition-colors hover:bg-brand-azure-600 medium:inline-flex"
                    @if (collect($navigationItems)->firstWhere('key', 'contact')['active'] ?? false) aria-current="page" @endif
                >{{ __('ui.header.cta') }}</a>

                <details class="group relative wide:hidden" data-public-mobile-nav>
                    <summary
                        class="inline-flex size-10 cursor-pointer list-none items-center justify-center rounded-control border border-line text-brand-royal marker:content-none hover:bg-surface-subtle [&::-webkit-details-marker]:hidden"
                        aria-label="{{ __('ui.navigation.menu') }}"
                    >
                        <span class="sr-only">{{ __('ui.navigation.menu') }}</span>
                        <span class="group-open:hidden" aria-hidden="true">
                            <x-public.icon name="menu" :size="18" />
                        </span>
                        <span class="hidden group-open:inline" aria-hidden="true">
                            <x-public.icon name="x" :size="18" />
                        </span>
                    </summary>

                    <div class="absolute top-[calc(100%+0.5rem)] right-0 z-50 w-[min(20rem,calc(100vw-2rem))] overflow-hidden rounded-panel border border-line bg-white shadow-card">
                        <nav class="p-2" aria-label="{{ __('ui.navigation.mobile') }}">
                            <ul class="grid gap-0.5">
                                @foreach ($navigationItems as $item)
                                    <li>
                                        <a
                                            href="{{ $item['url'] }}"
                                            data-primary-navigation-item="{{ $item['key'] }}"
                                            @class([
                                                'flex min-h-11 items-center rounded-control px-3 text-sm font-semibold transition-colors',
                                                'bg-surface-subtle text-brand-royal' => $item['active'],
                                                'text-ink hover:bg-surface-subtle hover:text-brand-royal' => ! $item['active'],
                                            ])
                                            @if ($item['active']) aria-current="page" @endif
                                        >{{ $item['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <a
                                href="{{ $contactUrl }}"
                                class="mt-2 flex min-h-11 items-center justify-center rounded-control bg-brand-royal px-4 text-sm font-bold text-white"
                            >{{ __('ui.header.cta') }}</a>
                        </nav>
                    </div>
                </details>
            </div>
        </div>
    </div>
</header>
