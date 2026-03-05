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
        return response("User-agent: *\nDisallow: /admin\nSitemap: https://lunarambalaj.com.tr/sitemap.xml\n", 200)
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
            '- TR Home: https://lunarambalaj.com.tr/',
            '- EN Home: https://lunarambalaj.com.tr/en',
            '- TR Products: https://lunarambalaj.com.tr/urunler',
            '- EN Products: https://lunarambalaj.com.tr/en/products',
            '- TR Solutions: https://lunarambalaj.com.tr/cozumler',
            '- EN Solutions: https://lunarambalaj.com.tr/en/solutions',
            '- TR Quote: https://lunarambalaj.com.tr/teklif-al',
            '- EN Quote: https://lunarambalaj.com.tr/en/get-quote',
            '- Sitemap: https://lunarambalaj.com.tr/sitemap.xml',
            '- Robots: https://lunarambalaj.com.tr/robots.txt',
            '',
            '## Notes',
            '- Default locale: tr',
            '- English pages use /en prefix',
            '- Lead forms: contact and quote',
            '',
        ]);

        return response($content, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function sitemap()
    {
        $urls = [
            '/', '/hakkimizda', '/hizmetler', '/urunler', '/cozumler', '/galeri', '/referanslar', '/sss', '/blog', '/iletisim', '/teklif-al', '/kvkk', '/cerez-politikasi', '/gizlilik-politikasi',
            '/en', '/en/about', '/en/services', '/en/products', '/en/solutions', '/en/gallery', '/en/references', '/en/faq', '/en/blog', '/en/contact', '/en/get-quote', '/en/kvkk', '/en/cookie-policy', '/en/privacy-policy',
            '/ru', '/ru/about', '/ru/services', '/ru/products', '/ru/solutions', '/ru/gallery', '/ru/references', '/ru/faq', '/ru/blog', '/ru/contact', '/ru/get-quote', '/ru/kvkk', '/ru/cookie-policy', '/ru/privacy-policy',
            '/ar', '/ar/about', '/ar/services', '/ar/products', '/ar/solutions', '/ar/gallery', '/ar/references', '/ar/faq', '/ar/blog', '/ar/contact', '/ar/get-quote', '/ar/kvkk', '/ar/cookie-policy', '/ar/privacy-policy',
        ];

        ProductTranslation::query()
            ->whereIn('lang', ['tr', 'en', 'ru', 'ar'])
            ->get()
            ->each(function (ProductTranslation $translation) use (&$urls): void {
                $prefix = match ($translation->lang) {
                    'tr' => '/urunler/',
                    'en' => '/en/products/',
                    'ru' => '/ru/products/',
                    'ar' => '/ar/products/',
                    default => null,
                };

                if ($prefix !== null) {
                    $urls[] = $prefix . $translation->slug;
                }
            });

        PostTranslation::query()
            ->whereIn('lang', ['tr', 'en', 'ru', 'ar'])
            ->get()
            ->each(function (PostTranslation $translation) use (&$urls): void {
                $prefix = match ($translation->lang) {
                    'tr' => '/blog/',
                    'en' => '/en/blog/',
                    'ru' => '/ru/blog/',
                    'ar' => '/ar/blog/',
                    default => null,
                };

                if ($prefix !== null) {
                    $urls[] = $prefix . $translation->slug;
                }
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

