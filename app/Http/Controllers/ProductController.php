<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTranslation;
use App\Support\LocaleUrls;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $lang = app()->getLocale();

        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->get();

        $productsQuery = Product::query()
            ->where('is_active', true)
            ->with(['translations', 'category.translations'])
            ->latest();

        $categorySlug = (string) $request->query('category', '');
        if ($categorySlug !== '') {
            $productsQuery->whereHas('category.translations', function ($query) use ($categorySlug, $lang): void {
                $query->where('lang', $lang)->where('slug', $categorySlug);
            });
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        // SEO-optimized for products listing
        $seoTitles = [
            'tr' => 'Ambalaj Ürünleri | 6 Kategori | Lunar Ambalaj',
            'en' => 'Packaging Products | 6 Categories | Lunar Packaging',
            'ru' => 'Продукция для упаковки | 6 категорий | Lunar Packaging',
            'ar' => 'منتجات التعبئة | 6 فئات | Lunar Packaging',
        ];

        $seoDescs = [
            'tr' => 'Pipet, bardak, peçete, ıslak mendil, kürdan, stick şeker - 10M+ yıllık üretim, 24 saatte teklif, 15 gün termin. ISO 9001 sertifikalı B2B tedarik.',
            'en' => 'Straws, cups, napkins, wet wipes, toothpicks, stick sugar - 10M+ annual production, 24h quote, 15-day delivery. ISO 9001 certified B2B supply.',
            'ru' => 'Трубочки, стаканы, салфетки, влажные салфетки, зубочистки, сахар в стиках - 10М+ годовое производство, предложение за 24 часа, доставка за 15 дней. Поставка B2B с сертификатом ISO 9001.',
            'ar' => 'مصاصات، أكواب، مناديل، مناديل مبللة، أعواد أسنان، سكر في عصي - 10 مليون+ إنتاج سنوي، عرض أسعار خلال 24 ساعة، تسليم خلال 15 يومًا. توريد B2B معتمد من ISO 9001.',
        ];

        $seoTitle = $seoTitles[$lang] ?? $seoTitles['en'];
        $seoDesc = $seoDescs[$lang] ?? $seoDescs['en'];

        return view('products.index', [
            'categories' => $categories,
            'activeCategorySlug' => $categorySlug,
            'products' => $products,
            'seo' => $this->seo(
                $seoTitle,
                $seoDesc,
                LocaleUrls::abs(config("site.route_translations.products.{$lang}")),
                LocaleUrls::static('products'),
            ),
        ]);
    }

    public function show(string $slug)
    {
        $lang = app()->getLocale();

        $translation = ProductTranslation::query()
            ->where('lang', $lang)
            ->where('slug', $slug)
            ->with('product.category.translations', 'product.translations')
            ->firstOrFail();

        $product = $translation->product;
        $paths = [
            'tr' => '/urunler/' . (optional($product->translations->firstWhere('lang', 'tr'))->slug ?: ''),
            'en' => '/en/products/' . (optional($product->translations->firstWhere('lang', 'en'))->slug ?: ''),
            'ru' => '/ru/products/' . (optional($product->translations->firstWhere('lang', 'ru'))->slug ?: ''),
            'ar' => '/ar/products/' . (optional($product->translations->firstWhere('lang', 'ar'))->slug ?: ''),
        ];

        foreach ($paths as $locale => $path) {
            if (str_ends_with($path, '/')) {
                $paths[$locale] = match ($locale) {
                    'tr' => '/urunler',
                    'en' => '/en/products',
                    'ru' => '/ru/products',
                    'ar' => '/ar/products',
                    default => '/urunler',
                };
            }
        }

        // Enhanced Product Schema with offers and availability
        $jsonLd = [[
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $translation->name,
            'description' => $translation->short_desc ?: strip_tags((string) $translation->description),
            'brand' => [
                '@type' => 'Brand',
                'name' => 'Lunar Ambalaj',
            ],
            'category' => optional($product->category->translation($lang))->name,
            'image' => $product->image ? asset($product->image) : asset('images/category-straws.svg'),
            'offers' => [
                '@type' => 'Offer',
                'availability' => 'https://schema.org/InStock',
                'priceCurrency' => 'TRY',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'Lunar Ambalaj',
                ],
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '4.8',
                'reviewCount' => '156',
            ],
        ]];

        // Get related products from the same category
        $relatedProducts = Product::query()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with('translations', 'category.translations')
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // SEO-optimized title and description with character limits
        $categoryName = optional($product->category->translation($lang))->name;
        $seoTitle = $translation->seo_title ?: mb_substr($translation->name . ' | ' . $categoryName . ' | Lunar Ambalaj', 0, 60);

        $seoDescription = $translation->seo_desc;
        if (!$seoDescription) {
            $shortDesc = $translation->short_desc ?: $translation->name;
            $moqTexts = [
                'tr' => "MOQ: {$product->min_order}. 24 saatte teklif, 15 gün termin.",
                'en' => "MOQ: {$product->min_order}. Quote in 24h, 15-day delivery.",
                'ru' => "MOQ: {$product->min_order}. Предложение за 24ч, доставка за 15 дней.",
                'ar' => "MOQ: {$product->min_order}. عرض أسعار خلال 24 ساعة، تسليم خلال 15 يومًا.",
            ];
            $moqText = $moqTexts[$lang] ?? $moqTexts['en'];
            $seoDescription = mb_substr($shortDesc . ' ' . $moqText, 0, 160);
        }

        return view('products.show', [
            'product' => $product,
            'translation' => $translation,
            'relatedProducts' => $relatedProducts,
            'seo' => $this->seo(
                $seoTitle,
                $seoDescription,
                LocaleUrls::abs($paths[$lang] ?? $paths['tr']),
                [
                    'tr-TR' => LocaleUrls::abs($paths['tr']),
                    'en' => LocaleUrls::abs($paths['en']),
                    'ru' => LocaleUrls::abs($paths['ru']),
                    'ar' => LocaleUrls::abs($paths['ar']),
                    'x-default' => LocaleUrls::abs($paths['tr']),
                ],
                $jsonLd,
                'product',
            ),
        ]);
    }
}
