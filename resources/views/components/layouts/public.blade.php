@props([
    'title' => config('app.name'),
    'description' => null,
    'canonical' => null,
    'hreflang' => [],
    'image' => null,
    'type' => 'website',
    'robots' => null,
])

@php
    use App\Enums\Locale;
    use App\Support\OpenGraph;

    $robotsDirective = is_string($robots) && $robots !== ''
        ? $robots
        : OpenGraph::robots();
    $ogImage = filled($image) ? OpenGraph::absoluteUrl((string) $image) : OpenGraph::fallbackImageUrl();
    $ogLocale = OpenGraph::localeTag(app()->getLocale());
    $alternateOgLocales = collect(is_array($hreflang) ? array_keys($hreflang) : [])
        ->reject(fn (string|int $code): bool => (string) $code === app()->getLocale())
        ->map(fn (string|int $code): string => OpenGraph::localeTag((string) $code))
        ->values();
    $xDefault = is_array($hreflang)
        ? ($hreflang[Locale::French->value] ?? $hreflang[Locale::English->value] ?? null)
        : null;
    $siteName = (string) config('app.name');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="{{ $robotsDirective }}">
        <meta name="theme-color" content="#0E6697">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        @if (filled($description))
            <meta name="description" content="{{ $description }}">
        @endif

        @if (filled($canonical))
            <link rel="canonical" href="{{ $canonical }}">
        @endif

        @foreach ($hreflang as $locale => $url)
            <link rel="alternate" hreflang="{{ $locale }}" href="{{ $url }}">
        @endforeach

        @if (filled($xDefault))
            <link rel="alternate" hreflang="x-default" href="{{ $xDefault }}">
        @endif

        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:type" content="{{ $type }}">
        <meta property="og:title" content="{{ $title }}">
        @if (filled($description))
            <meta property="og:description" content="{{ $description }}">
        @endif
        @if (filled($canonical))
            <meta property="og:url" content="{{ $canonical }}">
        @endif
        @if (filled($ogImage))
            <meta property="og:image" content="{{ $ogImage }}">
        @endif
        <meta property="og:locale" content="{{ $ogLocale }}">
        @foreach ($alternateOgLocales as $alternateLocale)
            <meta property="og:locale:alternate" content="{{ $alternateLocale }}">
        @endforeach

        <meta name="twitter:card" content="{{ filled($ogImage) ? 'summary_large_image' : 'summary' }}">
        <meta name="twitter:title" content="{{ $title }}">
        @if (filled($description))
            <meta name="twitter:description" content="{{ $description }}">
        @endif
        @if (filled($ogImage))
            <meta name="twitter:image" content="{{ $ogImage }}">
        @endif

        @isset($head)
            {{ $head }}
        @endisset

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-surface-canvas font-sans text-ink antialiased" data-public-shell="true">
        <a
            href="#main-content"
            class="fixed top-4 left-4 z-50 -translate-y-24 rounded-control bg-brand-royal px-4 py-3 font-bold text-white shadow-card transition-transform duration-[var(--motion-fast)] focus:translate-y-0"
        >{{ __('ui.skip_to_content') }}</a>

        <div class="flex min-h-screen flex-col">
            <x-public.site-header :hreflang="$hreflang" />

            <main id="main-content" class="flex-1" tabindex="-1">
                {{ $slot }}
            </main>

            <x-public.site-footer />
        </div>

        @isset($scripts)
            {{ $scripts }}
        @endisset
    </body>
</html>
