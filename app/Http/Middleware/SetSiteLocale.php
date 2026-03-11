<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetSiteLocale
{
    public function handle(Request $request, Closure $next, ?string $forcedLocale = null): Response
    {
        $supportedLocales = config('site.locales', ['tr', 'en']);
        $defaultLocale = config('site.default_locale', 'tr');

        // Determine locale from forced parameter or the first path segment.
        $locale = $forcedLocale ?: $request->segment(1) ?: $defaultLocale;

        // Validate locale is supported
        if (! in_array($locale, $supportedLocales, true)) {
            $locale = $defaultLocale;
        }

        App::setLocale($locale);

        if ($request->cookie('site_lang') !== $locale) {
            Cookie::queue(cookie('site_lang', $locale, 60 * 24 * 365));
        }

        return $next($request);
    }
}
