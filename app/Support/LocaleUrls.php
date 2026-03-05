<?php

namespace App\Support;

class LocaleUrls
{
    public static function static(string $key): array
    {
        $map = config("site.route_translations.{$key}", []);

        if (! isset($map['tr'])) {
            return [];
        }

        $urls = ['tr-TR' => self::abs($map['tr'])];

        foreach (['en', 'ru', 'ar'] as $locale) {
            if (isset($map[$locale])) {
                $urls[$locale] = self::abs($map[$locale]);
            }
        }

        $urls['x-default'] = $urls['tr-TR'];

        return $urls;
    }

    public static function abs(string $path): string
    {
        return rtrim(config('app.url'), '/') . $path;
    }
}
