<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostTranslation;
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

        // SEO-optimized for blog listing
        $seoTitles = [
            'tr' => 'Blog | Ambalaj Trendleri ve B2B Çözümler | Lunar Ambalaj',
            'en' => 'Blog | Packaging Trends & B2B Solutions | Lunar Packaging',
            'ru' => 'Блог | Тренды упаковки и решения B2B | Lunar Packaging',
            'ar' => 'المدونة | اتجاهات التعبئة وحلول B2B | Lunar Packaging',
        ];

        $seoDescs = [
            'tr' => 'Ambalaj sektörü, baskı planlama, MOQ yönetimi, kategori seçimi rehberleri. B2B tedarik ve sipariş operasyonları için içerikler.',
            'en' => 'Packaging industry insights, print planning, MOQ management, category selection guides. Content for B2B supply and order operations.',
            'ru' => 'Аналитика упаковочной индустрии, планирование печати, управление MOQ, руководства по выбору категорий. Контент для операций поставки и заказа B2B.',
            'ar' => 'رؤى صناعة التعبئة وتخطيط الطباعة وإدارة الحد الأدنى للطلب وأدلة اختيار الفئات. محتوى لعمليات توريد وطلب B2B.',
        ];

        $seoTitle = $seoTitles[$lang] ?? $seoTitles['en'];
        $seoDesc = $seoDescs[$lang] ?? $seoDescs['en'];

        return view('blog.index', [
            'posts' => $posts,
            'seo' => $this->seo(
                $seoTitle,
                $seoDesc,
                LocaleUrls::abs(config("site.route_translations.blog.{$lang}")),
                LocaleUrls::static('blog'),
            ),
        ]);
    }

    public function show(string $slug)
    {
        $lang = app()->getLocale();

        $translation = PostTranslation::query()
            ->where('lang', $lang)
            ->where('slug', $slug)
            ->with('post.translations')
            ->firstOrFail();

        $post = $translation->post;
        $trSlug = optional($post->translations->firstWhere('lang', 'tr'))->slug;
        $enSlug = optional($post->translations->firstWhere('lang', 'en'))->slug;

        $trPath = $trSlug ? '/blog/' . $trSlug : '/blog';
        $enPath = $enSlug ? '/en/blog/' . $enSlug : '/en/blog';

        // Get related posts from same category or latest posts
        $relatedPosts = Post::query()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->with('translations')
            ->latest('published_at')
            ->limit(3)
            ->get();

        // BlogPosting schema markup
        $jsonLd = [[
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $translation->title,
            'description' => $translation->short_desc ?: mb_substr(strip_tags($translation->body), 0, 200),
            'image' => $post->cover ? asset($post->cover) : asset('images/hero-bg.png'),
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

        // SEO-optimized title and description
        $seoTitle = $translation->seo_title ?: mb_substr($translation->title . ' | Lunar Ambalaj Blog', 0, 60);

        $seoDescription = $translation->seo_desc;
        if (!$seoDescription) {
            $bodyPreview = strip_tags($translation->body);
            $readMoreTexts = [
                'tr' => ' Detaylı bilgi için blog yazımızı okuyun.',
                'en' => ' Read our blog post for details.',
                'ru' => ' Читайте нашу запись в блоге для получения подробной информации.',
                'ar' => ' اقرأ مقالتنا للحصول على التفاصيل.',
            ];
            $readMoreText = $readMoreTexts[$lang] ?? $readMoreTexts['en'];
            $seoDescription = mb_substr($bodyPreview . $readMoreText, 0, 160);
        }

        return view('blog.show', [
            'translation' => $translation,
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'seo' => $this->seo(
                $seoTitle,
                $seoDescription,
                LocaleUrls::abs($lang === 'tr' ? $trPath : $enPath),
                [
                    'tr-TR' => LocaleUrls::abs($trPath),
                    'en' => LocaleUrls::abs($enPath),
                    'x-default' => LocaleUrls::abs($trPath),
                ],
                $jsonLd,
                'article',
                $post->cover,
            ),
        ]);
    }
}
