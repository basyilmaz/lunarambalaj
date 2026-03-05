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
        // Determine locale from forced parameter or URL path
        if ($forcedLocale) {
            $locale = $forcedLocale;
        } elseif ($request->is('en') || $request->is('en/*')) {
            $locale = 'en';
        } elseif ($request->is('ru') || $request->is('ru/*')) {
            $locale = 'ru';
        } elseif ($request->is('ar') || $request->is('ar/*')) {
            $locale = 'ar';
        } else {
            $locale = 'tr'; // Default to Turkish
        }

        // Validate locale is supported
        if (! in_array($locale, ['tr', 'en', 'ru', 'ar'], true)) {
            $locale = 'tr';
        }

        App::setLocale($locale);
        Cookie::queue(cookie('site_lang', $locale, 60 * 24 * 365));

        return $next($request);
    }
}
