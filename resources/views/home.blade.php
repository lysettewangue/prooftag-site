<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('ui.home_heading') }} — {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white text-neutral-900 antialiased">
        <header class="mx-auto flex max-w-3xl items-center justify-between gap-4 px-6 py-6">
            <a href="{{ locale_route('home') }}">{{ config('app.name') }}</a>
            <nav aria-label="Language">
                <a
                    href="{{ locale_route('home', locale: 'fr') }}"
                    hreflang="fr"
                    lang="fr"
                    @class(['underline' => app()->getLocale() === 'fr'])
                >{{ __('ui.switch_to_fr') }}</a>
                <span aria-hidden="true"> · </span>
                <a
                    href="{{ locale_route('home', locale: 'en') }}"
                    hreflang="en"
                    lang="en"
                    @class(['underline' => app()->getLocale() === 'en'])
                >{{ __('ui.switch_to_en') }}</a>
            </nav>
        </header>
        <main class="mx-auto max-w-3xl px-6 py-12">
            <h1 class="text-3xl font-semibold">{{ __('ui.home_heading') }}</h1>
            <p class="mt-4 text-lg text-neutral-700">{{ __('ui.home_body') }}</p>
        </main>
    </body>
</html>
