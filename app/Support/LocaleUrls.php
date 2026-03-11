<?php

namespace App\Support;

class LocaleUrls
{
    public static function static(string $key): array
    {
        $map = config("site.route_translations.{$key}", []);
        $defaultLocale = config('site.default_locale', 'tr');
        $supportedLocales = config('site.locales', ['tr', 'en']);

        if (! isset($map[$defaultLocale])) {
            return [];
        }

        $urls = [];

        foreach ($supportedLocales as $locale) {
            if (isset($map[$locale])) {
                $hreflang = $locale === 'tr' ? 'tr-TR' : $locale;
                $urls[$hreflang] = self::abs($map[$locale]);
            }
        }

        $defaultHrefLang = $defaultLocale === 'tr' ? 'tr-TR' : $defaultLocale;
        $urls['x-default'] = $urls[$defaultHrefLang] ?? self::abs($map[$defaultLocale]);

        return $urls;
    }

    public static function abs(string $path): string
    {
        return rtrim(config('site.canonical_url', config('app.url')), '/') . $path;
    }
}
