<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\Redirect;

final class RedirectResolver
{
    public function resolve(string $host, string $path): ?Redirect
    {
        $host = strtolower(preg_replace('/:\\d+$/', '', $host) ?? $host);
        $path = '/'.ltrim($path, '/');

        if ($path !== '/') {
            $path = rtrim($path, '/') ?: '/';
        }

        $exact = Redirect::query()
            ->where('is_active', true)
            ->where('from_host', $host)
            ->where('from_path', $path)
            ->first();

        if ($exact !== null) {
            return $exact;
        }

        $candidates = Redirect::query()
            ->where('is_active', true)
            ->where('from_host', $host)
            ->where('from_path', '!=', $path)
            ->orderByRaw('CHAR_LENGTH(from_path) DESC')
            ->get();

        foreach ($candidates as $redirect) {
            $prefix = rtrim($redirect->from_path, '/');

            if ($prefix !== '' && ($path === $prefix || str_starts_with($path, $prefix.'/'))) {
                return $redirect;
            }
        }

        return null;
    }
}
