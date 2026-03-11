<?php

namespace App\Http\Controllers;

use App\Models\PostTranslation;
use App\Models\ProductCategory;
use App\Models\ProductTranslation;
use App\Models\ServiceItem;
use App\Models\Setting;
use App\Support\LocaleUrls;

class SeoController extends Controller
{
    public function robots()
    {
        $baseUrl = rtrim(config('site.canonical_url', config('app.url', 'https://lunarambalaj.com')), '/');

        return response("User-agent: *\nDisallow: /admin\nSitemap: {$baseUrl}/sitemap.xml\n", 200)
            ->header('Content-Type', 'text/plain');
    }

    public function llms()
    {
        $setting = Setting::query()->first();

        $serviceItems = ServiceItem::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->get();

        $services = $serviceItems->map(function (ServiceItem $item): string {
            $tr = $item->translation('tr')?->title;
            $en = $item->translation('en')?->title;

            return '- ' . trim(($tr ?: 'Service') . ($en ? ' / ' . $en : ''));
        })->all();

        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(function (ProductCategory $category): string {
                $tr = $category->translation('tr')?->name;
                $en = $category->translation('en')?->name;

                return '- ' . trim(($tr ?: 'Category') . ($en ? ' / ' . $en : ''));
            })->all();

        $baseUrl = rtrim(config('site.canonical_url', config('app.url', 'https://lunarambalaj.com')), '/');
        $locales = config('site.locales', ['tr', 'en']);

        $importantUrls = [];
        foreach ($locales as $locale) {
            $prefix = $locale === 'tr' ? '' : '/' . $locale;
            $label = strtoupper($locale);

            $importantUrls[] = "- {$label} Home: {$baseUrl}" . ($prefix === '' ? '/' : $prefix);

            foreach (['products', 'solutions', 'quote'] as $routeKey) {
                $path = config("site.route_translations.{$routeKey}.{$locale}");
                if (is_string($path) && $path !== '') {
                    $importantUrls[] = "- {$label} " . ucfirst($routeKey) . ': ' . $baseUrl . $path;
                }
            }
        }

        $importantUrls[] = '- Sitemap: ' . $baseUrl . '/sitemap.xml';
        $importantUrls[] = '- Robots: ' . $baseUrl . '/robots.txt';

        $content = implode("\n", [
            '# Lunar Ambalaj',
            '',
            '## Company Summary',
            'Lunar Ambalaj is an Istanbul-based manufacturer for food service consumables.',
            'Core groups: plastic frozen straws, multifunction/custom-size straws, cups, napkins, wet wipes, flag toothpicks and stick sugar.',
            'Paper straws are offered in contract manufacturing mode on demand.',
            'The company operates as a single-supplier B2B partner with custom printing, planned lead times and quote-driven production.',
            '',
            '## Contact',
            '- Phone: ' . ($setting?->phone ?: 'N/A'),
            '- Email: ' . ($setting?->email ?: 'N/A'),
            '- Secondary Email: ' . ($setting?->email_secondary ?: 'N/A'),
            '- Address: ' . ($setting?->address ?: 'N/A'),
            '- Working Hours: ' . str_replace("\n", ' | ', (string) ($setting?->working_hours ?: 'N/A')),
            '',
            '## Service List',
            ...$services,
            '',
            '## Product Categories',
            ...$categories,
            '',
            '## Important URLs',
            ...$importantUrls,
            '',
            '## Notes',
            '- Default locale: tr',
            '- Language pages use locale prefixes (except tr)',
            '- Lead forms: contact and quote',
            '',
        ]);

        return response($content, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function sitemap()
    {
        $locales = config('site.locales', ['tr', 'en']);
        $routeTranslations = config('site.route_translations', []);

        $urls = ['/'];
        foreach ($locales as $locale) {
            if ($locale !== 'tr') {
                $urls[] = '/' . $locale;
            }
        }

        foreach ($routeTranslations as $routeMap) {
            if (! is_array($routeMap)) {
                continue;
            }

            foreach ($locales as $locale) {
                $path = $routeMap[$locale] ?? null;
                if (is_string($path) && $path !== '') {
                    $urls[] = $path;
                }
            }
        }

        $productPrefixes = collect($locales)
            ->mapWithKeys(static fn (string $locale): array => [$locale => rtrim((string) config("site.route_translations.products.{$locale}", ''), '/') . '/'])
            ->all();

        ProductTranslation::query()
            ->whereIn('lang', $locales)
            ->get()
            ->each(function (ProductTranslation $translation) use (&$urls, $productPrefixes): void {
                $prefix = $productPrefixes[$translation->lang] ?? null;
                if ($prefix === null || $prefix === '/') {
                    return;
                }
                $urls[] = $prefix . $translation->slug;
            });

        $blogPrefixes = collect($locales)
            ->mapWithKeys(static fn (string $locale): array => [$locale => rtrim((string) config("site.route_translations.blog.{$locale}", ''), '/') . '/'])
            ->all();

        PostTranslation::query()
            ->whereIn('lang', $locales)
            ->get()
            ->each(function (PostTranslation $translation) use (&$urls, $blogPrefixes): void {
                $prefix = $blogPrefixes[$translation->lang] ?? null;
                if ($prefix === null || $prefix === '/') {
                    return;
                }
                $urls[] = $prefix . $translation->slug;
            });

        $urls = array_unique($urls);

        $xmlItems = collect($urls)->map(function (string $path): string {
            return '<url><loc>' . e(LocaleUrls::abs($path)) . '</loc><lastmod>' . now()->toDateString() . '</lastmod></url>';
        })->implode('');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
            . $xmlItems
            . '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}

