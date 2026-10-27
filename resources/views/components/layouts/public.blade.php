@props([
    'title' => config('app.name'),
    'description' => null,
    'canonical' => null,
    'hreflang' => [],
    'robots' => 'index,follow',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="{{ $robots }}">
        <meta name="theme-color" content="#007C9E">

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

        @isset($head)
            {{ $head }}
        @endisset

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-surface-canvas font-sans text-ink antialiased" data-public-shell="true">
        <a
            href="#main-content"
            class="fixed top-4 left-4 z-50 -translate-y-24 rounded-control bg-brand-azure-700 px-4 py-3 font-bold text-white shadow-card transition-transform duration-[var(--motion-fast)] focus:translate-y-0"
        >{{ __('ui.skip_to_content') }}</a>

        <div class="flex min-h-screen flex-col">
            <x-public.site-header />

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
