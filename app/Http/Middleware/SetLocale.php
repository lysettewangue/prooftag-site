<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $locale): Response
    {
        $available = config('app.available_locales', ['fr', 'en']);

        if (! in_array($locale, $available, true)) {
            abort(404);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
