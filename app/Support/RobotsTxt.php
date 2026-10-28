<?php

declare(strict_types=1);

namespace App\Support;

final class RobotsTxt
{
    public static function body(): string
    {
        $sitemap = rtrim((string) config('cms.canonical_host'), '/').'/sitemap.xml';
        $indexable = ! str_contains(strtolower(OpenGraph::robots()), 'noindex');

        if (! $indexable) {
            return implode("\n", [
                'User-agent: *',
                'Disallow: /',
                '',
                'Sitemap: '.$sitemap,
                '',
            ]);
        }

        return implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /admin/',
            '',
            'Sitemap: '.$sitemap,
            '',
        ]);
    }
}
