<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostTranslation;
use App\Support\AssetVariant;
use App\Support\LocaleUrls;

class BlogController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();

        $posts = Post::query()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with('translations')
            ->latest('published_at')
            ->paginate(9);

        $seoTitles = [
            'tr' => 'Blog | Ambalaj Trendleri ve B2B Çözümler | Lunar Ambalaj',
            'en' => 'Blog | Packaging Trends & B2B Solutions | Lunar Packaging',
            'ru' => 'Блог | Тренды упаковки и B2B решения | Lunar Packaging',
            'ar' => 'المدونة | اتجاهات التعبئة وحلول B2B | Lunar Packaging',
            'es' => 'Blog | Tendencias de Empaque y Soluciones B2B | Lunar Ambalaj',
        ];
        $seoDescs = [
            'tr' => 'Ambalaj sektörü, baskı planlama, MOQ yönetimi ve kategori seçimi rehberleri. B2B tedarik operasyonları için içerikler.',
            'en' => 'Packaging insights, print planning, MOQ management and category selection guides for B2B supply operations.',
            'ru' => 'Аналитика упаковки, планирование печати, управление MOQ и выбор категорий для B2B-поставок.',
            'ar' => 'محتوى عن قطاع التعبئة وتخطيط الطباعة وإدارة MOQ واختيار الفئات لعمليات توريد B2B.',
            'es' => 'Contenidos sobre tendencias de empaque, planificación de impresión, gestión de MOQ y selección de categorías para operaciones B2B.',
        ];

        return view('blog.index', [
            'posts' => $posts,
            'seo' => $this->seo(
                $seoTitles[$lang] ?? $seoTitles['en'],
                $seoDescs[$lang] ?? $seoDescs['en'],
                LocaleUrls::abs(config("site.route_translations.blog.{$lang}")),
                LocaleUrls::static('blog'),
            ),
        ]);
    }

    public function show(string $slug)
    {
        $lang = app()->getLocale();

        $lookupLocales = collect([$lang, config('app.fallback_locale', 'en'), 'tr', 'en'])
            ->filter()
            ->unique()
            ->values();

        $translation = null;
        foreach ($lookupLocales as $lookupLocale) {
            $translation = PostTranslation::query()
                ->where('lang', (string) $lookupLocale)
                ->where('slug', $slug)
                ->with('post.translations')
                ->first();

            if ($translation !== null) {
                break;
            }
        }

        abort_if($translation === null, 404);

        $post = $translation->post;
        $paths = [
            'tr' => '/blog/' . (optional($post->translations->firstWhere('lang', 'tr'))->slug ?: ''),
            'en' => '/en/blog/' . (optional($post->translations->firstWhere('lang', 'en'))->slug ?: ''),
            'ru' => '/ru/blog/' . (optional($post->translations->firstWhere('lang', 'ru'))->slug ?: ''),
            'ar' => '/ar/blog/' . (optional($post->translations->firstWhere('lang', 'ar'))->slug ?: ''),
            'es' => '/es/blog/' . (optional($post->translations->firstWhere('lang', 'es'))->slug ?: ''),
        ];

        foreach ($paths as $locale => $path) {
            if (!str_ends_with($path, '/')) {
                continue;
            }

            $paths[$locale] = match ($locale) {
                'tr' => '/blog',
                'en' => '/en/blog',
                'ru' => '/ru/blog',
                'ar' => '/ar/blog',
                'es' => '/es/blog',
                default => '/blog',
            };
        }

        $relatedPosts = Post::query()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->with('translations')
            ->latest('published_at')
            ->limit(3)
            ->get();

        $jsonLd = [[
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $translation->title,
            'description' => $translation->short_desc ?: mb_substr(strip_tags($translation->body), 0, 200),
            'image' => asset(AssetVariant::optimized($post->cover, 'images/hero-bg.webp')),
            'author' => [
                '@type' => 'Organization',
                'name' => 'Lunar Ambalaj',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Lunar Ambalaj',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.svg'),
                ],
            ],
            'datePublished' => optional($post->published_at)->toIso8601String(),
            'dateModified' => optional($post->updated_at)->toIso8601String(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => url()->current(),
            ],
        ]];

        $seoTitle = $translation->seo_title ?: mb_substr($translation->title . ' | Lunar Ambalaj Blog', 0, 60);
        $seoDescription = $translation->seo_desc;
        if (!$seoDescription) {
            $bodyPreview = strip_tags($translation->body);
            $readMoreTexts = [
                'tr' => ' Detaylı bilgi için yazıyı okuyun.',
                'en' => ' Read our article for more details.',
                'ru' => ' Прочитайте статью для подробностей.',
                'ar' => ' اقرأ المقال للمزيد من التفاصيل.',
                'es' => ' Lee el artículo para más detalles.',
            ];
            $seoDescription = mb_substr($bodyPreview . ($readMoreTexts[$lang] ?? $readMoreTexts['en']), 0, 160);
        }

        return view('blog.show', [
            'translation' => $translation,
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'seo' => $this->seo(
                $seoTitle,
                $seoDescription,
                LocaleUrls::abs($paths[$lang] ?? $paths['tr']),
                [
                    'tr-TR' => LocaleUrls::abs($paths['tr']),
                    'en' => LocaleUrls::abs($paths['en']),
                    'ru' => LocaleUrls::abs($paths['ru']),
                    'ar' => LocaleUrls::abs($paths['ar']),
                    'es' => LocaleUrls::abs($paths['es']),
                    'x-default' => LocaleUrls::abs($paths['tr']),
                ],
                $jsonLd,
                'article',
                AssetVariant::optimized($post->cover, 'images/hero-bg.webp'),
            ),
        ]);
    }
}
