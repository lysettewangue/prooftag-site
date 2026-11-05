<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Seo\RedirectResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ApplyLegacyRedirect
{
    public function __construct(
        private readonly RedirectResolver $resolver,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = '/'.ltrim($request->getPathInfo(), '/');

        if ($this->shouldSkip($path)) {
            return $next($request);
        }

        $host = strtolower($request->getHost());
        $redirect = $this->resolver->resolve($host, $path);

        if ($redirect === null) {
            return $next($request);
        }

        $canonicalHost = parse_url((string) config('cms.canonical_host'), PHP_URL_HOST);
        $toPath = $redirect->to_path;

        if (is_string($canonicalHost) && $host === strtolower($canonicalHost) && $this->normalisedPath($path) === $this->normalisedPath($toPath)) {
            return $next($request);
        }

        $location = rtrim((string) config('cms.canonical_host'), '/').$toPath;

        return redirect()->away($location, $redirect->status_code);
    }

    private function shouldSkip(string $path): bool
    {
        $prefixes = [
            '/admin',
            '/livewire',
            '/storage',
            '/build',
            '/vendor',
            '/up',
        ];

        foreach ($prefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return true;
            }
        }

        return false;
    }

    private function normalisedPath(string $path): string
    {
        $path = '/'.ltrim($path, '/');

        if ($path !== '/') {
            $path = rtrim($path, '/') ?: '/';
        }

        return $path;
    }
}
