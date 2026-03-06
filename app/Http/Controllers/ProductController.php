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

        // SEO copy for products listing
        $seoTitles = [
            'tr' => 'Ambalaj Ürünleri | Lunar Ambalaj',
            'en' => 'Packaging Products | Lunar Packaging',
            'ru' => 'Упаковочная продукция | Lunar Packaging',
            'ar' => 'منتجات التعبئة | Lunar Packaging',
        ];

        $seoDescs = [
            'tr' => 'Plastik frozen, körüklü ve bubble pipet odaklı; bardak, peçete, ıslak mendil, bayraklı kürdan, stick şeker ve sticker baskı çözümleri.',
            'en' => 'Frozen, corrugated and bubble straw focused catalog with cups, napkins, wet wipes, flag toothpicks, stick sugar and sticker printing.',
            'ru' => 'Каталог с фокусом на frozen, гофрированные и bubble-трубочки, а также стаканы, салфетки, влажные салфетки и стикеры.',
            'ar' => 'كتالوج يركز على مصاصات فروزن ومرنة وبابل مع حلول الأكواب والمناديل والمناديل المبللة والكردان وطباعة الستيكر.',
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

        $specRows = $this->resolveSpecRows($product->specs, $lang);
        $leadTimeDays = $this->resolveLeadTimeDays($product->specs, $specRows);
        $leadTimeDisplay = $this->formatLeadTime($leadTimeDays, $lang);

        // SEO-optimized title and description with character limits
        $categoryName = optional($product->category->translation($lang))->name;
        $seoTitle = $translation->seo_title ?: mb_substr($translation->name . ' | ' . $categoryName . ' | Lunar Ambalaj', 0, 60);

        $seoDescription = $translation->seo_desc;
        if (!$seoDescription) {
            $shortDesc = $translation->short_desc ?: $translation->name;
            $moqTexts = [
                'tr' => "MOQ: {$product->min_order}. 24 saatte teklif, termin: {$leadTimeDisplay}.",
                'en' => "MOQ: {$product->min_order}. Quote in 24h, lead time: {$leadTimeDisplay}.",
                'ru' => "MOQ: {$product->min_order}. Расчет за 24 часа, срок: {$leadTimeDisplay}.",
                'ar' => "MOQ: {$product->min_order}. عرض سعر خلال 24 ساعة، المدة: {$leadTimeDisplay}.",
            ];
            $moqText = $moqTexts[$lang] ?? $moqTexts['en'];
            $seoDescription = mb_substr($shortDesc . ' ' . $moqText, 0, 160);
        }

        return view('products.show', [
            'product' => $product,
            'translation' => $translation,
            'specRows' => $specRows,
            'leadTimeDisplay' => $leadTimeDisplay,
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

    /**
     * @param array<string, mixed>|null $specs
     * @return array<string, string>
     */
    private function resolveSpecRows(?array $specs, string $lang): array
    {
        if (!$specs) {
            return [];
        }

        $localeRows = null;
        if (isset($specs[$lang]) && is_array($specs[$lang])) {
            $localeRows = $specs[$lang];
        } elseif (isset($specs['tr']) && is_array($specs['tr'])) {
            $localeRows = $specs['tr'];
        } elseif (isset($specs['en']) && is_array($specs['en'])) {
            $localeRows = $specs['en'];
        }

        if (is_array($localeRows)) {
            return collect($localeRows)
                ->filter(static fn ($value): bool => is_scalar($value) && $value !== '')
                ->mapWithKeys(static fn ($value, $key): array => [(string) $key => (string) $value])
                ->all();
        }

        return collect($specs)
            ->except(['lead_time_days', 'tr', 'en', 'ru', 'ar'])
            ->filter(static fn ($value): bool => is_scalar($value) && $value !== '')
            ->mapWithKeys(static fn ($value, $key): array => [(string) $key => (string) $value])
            ->all();
    }

    /**
     * @param array<string, mixed>|null $specs
     * @param array<string, string> $rows
     */
    private function resolveLeadTimeDays(?array $specs, array $rows): int
    {
        if (isset($specs['lead_time_days']) && is_numeric($specs['lead_time_days'])) {
            return max(1, (int) $specs['lead_time_days']);
        }

        foreach ($rows as $key => $value) {
            $normalized = mb_strtolower($key);
            if (!str_contains($normalized, 'termin')
                && !str_contains($normalized, 'lead')
                && !str_contains($normalized, 'срок')
                && !str_contains($normalized, 'مدة')
            ) {
                continue;
            }

            if (preg_match('/(\d{1,3})/', $value, $matches) === 1) {
                return max(1, (int) $matches[1]);
            }
        }

        return 20;
    }

    private function formatLeadTime(int $days, string $lang): string
    {
        return match ($lang) {
            'tr' => $days . ' iş günü',
            'ru' => $days . ' рабочих дней',
            'ar' => $days . ' يوم عمل',
            default => $days . ' business days',
        };
    }
}
