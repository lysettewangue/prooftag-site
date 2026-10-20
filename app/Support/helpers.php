<?php

declare(strict_types=1);

if (! function_exists('locale_route')) {
    /**
     * Named route for the active (or given) public locale.
     * French has no URL prefix; English lives under /en.
     *
     * @param  array<string, mixed>  $parameters
     */
    function locale_route(string $name, array $parameters = [], ?string $locale = null, bool $absolute = true): string
    {
        $locale ??= app()->getLocale();

        return route($locale.'.'.$name, $parameters, $absolute);
    }
}
