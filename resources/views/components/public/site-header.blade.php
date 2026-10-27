@php
    $locale = app()->getLocale();
    $navigationItems = \App\Support\PublicNavigation::items($locale, request()->path());
    $searchUrl = \App\Support\PublicNavigation::searchUrl($locale);
    $contactUrl = collect($navigationItems)->firstWhere('key', 'contact')['url'] ?? locale_route('home');
@endphp

<header class="relative z-40 bg-white" data-public-shell-region="header">
    <div class="border-b border-brand-cyan-100 bg-surface-subtle">
        <div class="mx-auto flex min-h-9 max-w-site items-center justify-between gap-4 px-gutter-compact medium:px-gutter-medium">
            <p class="truncate text-xs font-semibold tracking-wide text-brand-azure-700">
                {{ __('ui.header.utility') }}
            </p>

            <nav class="flex shrink-0 items-center" aria-label="{{ __('ui.language_navigation') }}">
                <a
                    href="{{ locale_route('home', locale: 'fr') }}"
                    hreflang="fr"
                    lang="fr"
                    @class([
                        'inline-flex min-h-9 items-center border-b-2 px-3 text-xs font-bold transition-colors',
                        'border-brand-azure-700 text-brand-azure-700' => $locale === 'fr',
                        'border-transparent text-muted hover:text-brand-azure-700' => $locale !== 'fr',
                    ])
                    @if ($locale === 'fr') aria-current="page" @endif
                >FR</a>
                <a
                    href="{{ locale_route('home', locale: 'en') }}"
                    hreflang="en"
                    lang="en"
                    @class([
                        'inline-flex min-h-9 items-center border-b-2 px-3 text-xs font-bold transition-colors',
                        'border-brand-azure-700 text-brand-azure-700' => $locale === 'en',
                        'border-transparent text-muted hover:text-brand-azure-700' => $locale !== 'en',
                    ])
                    @if ($locale === 'en') aria-current="page" @endif
                >EN</a>
            </nav>
        </div>
    </div>

    <div class="border-b border-line bg-white/95 shadow-soft backdrop-blur-md">
        <div class="mx-auto flex min-h-20 max-w-site items-center gap-5 px-gutter-compact medium:min-h-24 medium:px-gutter-medium">
            <a
                href="{{ locale_route('home') }}"
                class="inline-flex min-h-target shrink-0 items-center rounded-control"
                aria-label="{{ __('ui.brand_home') }}"
            >
                <img
                    src="{{ asset('images/brand/logo.png') }}"
                    width="250"
                    height="72"
                    alt="{{ config('app.name') }}"
                    class="h-auto w-40 medium:w-48"
                >
            </a>

            <nav class="ml-auto hidden min-w-0 flex-1 items-stretch justify-end self-stretch nav:flex" aria-label="{{ __('ui.navigation.primary') }}">
                <ul class="flex items-stretch justify-end gap-1">
                    @foreach ($navigationItems as $item)
                        <li class="flex">
                            <a
                                href="{{ $item['url'] }}"
                                data-primary-navigation-item="{{ $item['key'] }}"
                                @class([
                                    'relative flex items-center whitespace-nowrap px-2 text-[0.76rem] font-bold text-ink transition-colors after:absolute after:right-2 after:bottom-0 after:left-2 after:h-0.5 after:rounded-pill after:transition-colors',
                                    'text-brand-azure-700 after:bg-brand-cyan-500' => $item['active'],
                                    'after:bg-transparent hover:text-brand-azure-700 hover:after:bg-brand-cyan-500' => ! $item['active'],
                                ])
                                @if ($item['active']) aria-current="page" @endif
                            >{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="ml-auto hidden shrink-0 items-center gap-2 nav:flex">
                <a
                    href="{{ $searchUrl }}"
                    class="inline-flex size-11 items-center justify-center rounded-pill border border-line text-sm font-bold text-brand-azure-700 transition-colors hover:border-brand-azure-600 hover:bg-surface-subtle"
                    aria-label="{{ __('ui.navigation.search') }}"
                >
                    <span aria-hidden="true">⌕</span>
                </a>
                <a href="{{ $contactUrl }}" class="inline-flex min-h-control items-center rounded-pill bg-brand-cyan-500 px-5 text-sm font-bold text-ink shadow-soft transition-colors hover:bg-brand-cyan-100">
                    {{ __('ui.header.cta') }}
                </a>
            </div>

            <a
                href="{{ $searchUrl }}"
                class="ml-auto inline-flex min-h-target items-center rounded-pill border border-line px-3 text-sm font-bold text-brand-azure-700 hover:bg-surface-subtle nav:hidden"
            >{{ __('ui.navigation.search') }}</a>
        </div>

        <details class="group border-t border-line bg-white nav:hidden">
            <summary class="mx-auto flex min-h-14 max-w-site cursor-pointer list-none items-center justify-between px-gutter-compact font-bold text-ink marker:content-none medium:px-gutter-medium">
                <span>{{ __('ui.navigation.menu') }}</span>
                <span class="grid w-6 gap-1.5" aria-hidden="true">
                    <span class="h-0.5 rounded-pill bg-brand-azure-700"></span>
                    <span class="h-0.5 rounded-pill bg-brand-cyan-500"></span>
                    <span class="h-0.5 rounded-pill bg-brand-azure-700"></span>
                </span>
            </summary>

            <nav class="mx-auto max-w-site px-gutter-compact pb-6 medium:px-gutter-medium" aria-label="{{ __('ui.navigation.mobile') }}">
                <ul class="grid gap-1 border-t border-line pt-3 medium:grid-cols-2 medium:gap-x-5">
                    @foreach ($navigationItems as $item)
                        <li>
                            <a
                                href="{{ $item['url'] }}"
                                data-primary-navigation-item="{{ $item['key'] }}"
                                @class([
                                    'flex min-h-12 items-center rounded-control px-3 text-sm font-semibold transition-colors',
                                    'bg-surface-subtle text-brand-azure-700' => $item['active'],
                                    'text-ink hover:bg-surface-subtle hover:text-brand-azure-700' => ! $item['active'],
                                ])
                                @if ($item['active']) aria-current="page" @endif
                            >{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                    <li class="medium:col-span-2">
                        <a href="{{ $contactUrl }}" class="mt-3 flex min-h-control items-center justify-center rounded-control bg-brand-cyan-500 px-4 text-sm font-bold text-ink">
                            {{ __('ui.header.cta') }}
                        </a>
                    </li>
                </ul>
            </nav>
        </details>
    </div>
</header>
