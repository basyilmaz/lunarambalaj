<?php

namespace App\Support;

class AdminLanguageOptions
{
    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $labels = [
            'tr' => 'Turkce',
            'en' => 'English',
            'ru' => 'Russian',
            'ar' => 'Arabic',
            'es' => 'Spanish',
        ];

        $locales = config('site.locales', ['tr', 'en']);
        $options = [];

        foreach ($locales as $locale) {
            $key = (string) $locale;
            $options[$key] = $labels[$key] ?? strtoupper($key);
        }

        return $options;
    }
}

