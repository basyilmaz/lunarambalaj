<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\PostTranslation;
use App\Models\ProductTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_all_public_routes_return_success(): void
    {
        $paths = ['/', '/robots.txt', '/sitemap.xml', '/llms.txt'];

        $localeRoutes = [
            'tr' => ['/hakkimizda', '/hizmetler', '/urunler', '/cozumler', '/galeri', '/referanslar', '/sss', '/blog', '/iletisim', '/teklif-al', '/teklif-al/tesekkurler', '/kvkk', '/cerez-politikasi', '/gizlilik-politikasi'],
            'en' => ['/en', '/en/about', '/en/services', '/en/products', '/en/solutions', '/en/gallery', '/en/references', '/en/faq', '/en/blog', '/en/contact', '/en/get-quote', '/en/get-quote/thank-you', '/en/kvkk', '/en/cookie-policy', '/en/privacy-policy'],
            'ru' => ['/ru', '/ru/about', '/ru/services', '/ru/products', '/ru/solutions', '/ru/gallery', '/ru/references', '/ru/faq', '/ru/blog', '/ru/contact', '/ru/get-quote', '/ru/get-quote/thank-you', '/ru/kvkk', '/ru/cookie-policy', '/ru/privacy-policy'],
            'ar' => ['/ar', '/ar/about', '/ar/services', '/ar/products', '/ar/solutions', '/ar/gallery', '/ar/references', '/ar/faq', '/ar/blog', '/ar/contact', '/ar/get-quote', '/ar/get-quote/thank-you', '/ar/kvkk', '/ar/cookie-policy', '/ar/privacy-policy'],
        ];

        $dynamicRoutes = [
            'tr' => ['product' => '/urunler/', 'post' => '/blog/'],
            'en' => ['product' => '/en/products/', 'post' => '/en/blog/'],
            'ru' => ['product' => '/ru/products/', 'post' => '/ru/blog/'],
            'ar' => ['product' => '/ar/products/', 'post' => '/ar/blog/'],
        ];

        $languages = Language::query()->pluck('code')->all();

        foreach ($languages as $code) {
            foreach ($localeRoutes[$code] ?? [] as $path) {
                $paths[] = $path;
            }

            if (! isset($dynamicRoutes[$code])) {
                continue;
            }

            $product = ProductTranslation::query()->where('lang', $code)->first();
            $post = PostTranslation::query()->where('lang', $code)->first();

            if ($product !== null) {
                $paths[] = $dynamicRoutes[$code]['product'] . $product->slug;
            }

            if ($post !== null) {
                $paths[] = $dynamicRoutes[$code]['post'] . $post->slug;
            }
        }

        foreach ($paths as $path) {
            $this->get($path)->assertOk();
        }
    }
}

