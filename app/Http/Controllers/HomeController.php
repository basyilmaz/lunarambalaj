<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ServiceItem;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Support\LocaleUrls;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();
        $cacheTtl = now()->addMinutes(10);

        $services = Cache::remember("home:{$lang}:services", $cacheTtl, function () {
            return ServiceItem::query()
                ->where('is_active', true)
                ->with('translations')
                ->orderBy('order')
                ->get();
        });

        $categories = Cache::remember("home:{$lang}:categories", $cacheTtl, function () {
            return ProductCategory::query()
                ->where('is_active', true)
                ->with('translations')
                ->orderBy('order')
                ->get();
        });

        $products = Cache::remember("home:{$lang}:products", $cacheTtl, function () {
            return Product::query()
                ->where('is_active', true)
                ->with(['translations', 'category.translations'])
                ->latest()
                ->take(6)
                ->get();
        });

        $posts = Cache::remember("home:{$lang}:posts", $cacheTtl, function () {
            return Post::query()
                ->where('is_active', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->with('translations')
                ->latest('published_at')
                ->take(3)
                ->get();
        });

        $canonical = LocaleUrls::abs($lang === 'tr' ? '/' : '/' . $lang);
        $alternates = [
            'tr-TR' => LocaleUrls::abs('/'),
            'en' => LocaleUrls::abs('/en'),
            'ru' => LocaleUrls::abs('/ru'),
            'ar' => LocaleUrls::abs('/ar'),
            'x-default' => LocaleUrls::abs('/'),
        ];
        $setting = Cache::remember('site:settings:first', $cacheTtl, function () {
            return Setting::query()->first();
        });
        
        // Attempt to get setting for lang, fallback to default translation key
        $dbHeroTitle = $setting?->{'hero_h1_' . $lang} ?? null;
        $heroTitle = $dbHeroTitle ?: __('site.hero.title');
        
        $dbHeroSubtitle = $setting?->{'hero_subtitle_' . $lang} ?? null;
        $heroSubtitle = $dbHeroSubtitle ?: __('site.hero.subtitle');
        
        $dbCompanyName = $setting?->{'company_name_' . $lang} ?? null;
        $companyName = $dbCompanyName ?: __('site.brand');

        // Hero Slides (3 slides for carousel)
        if ($lang === 'tr') {
            $heroSlides = [
                [
                    'subtitle' => 'Endüstriyel Çözümler',
                    'title' => 'Türkiye\'nin Plastik Frozen Pipet Üretim Lideri',
                    'description' => 'Özel baskı, hızlı termin, stabil kalite - B2B çözümleriniz için tek adres',
                    'cta1' => ['text' => 'Ürünleri Keşfedin', 'href' => '/urunler'],
                    'cta2' => ['text' => 'İletişime Geçin', 'href' => '/iletisim'],
                ],
                [
                    'subtitle' => 'Geniş Ürün Yelpazesi',
                    'title' => '7 Kategori, Tek Tedarikçi',
                    'description' => 'Pipet, bardak, peçete, ıslak mendil, kürdan, stick şeker ve sticker baskı - Horeca segmentine özel',
                    'cta1' => ['text' => 'Kategorileri Gör', 'href' => '/urunler'],
                    'cta2' => ['text' => 'Teklif Al', 'href' => '/teklif-al'],
                ],
                [
                    'subtitle' => 'Hızlı ve Güvenilir',
                    'title' => '24 Saatte Teklif, 20 Günde Teslimat',
                    'description' => 'Hızlı yanıt, esnek planlama, güvenilir tedarik - işinize odaklanın',
                    'cta1' => ['text' => 'Hemen Teklif Al', 'href' => '/teklif-al'],
                    'cta2' => ['text' => 'Hizmetlerimiz', 'href' => '/hizmetler'],
                ],
            ];
        } elseif ($lang === 'ru') {
            $heroSlides = [
                [
                    'subtitle' => 'Промышленные решения',
                    'title' => 'Ведущий производитель пластиковых трубочек в Турции',
                    'description' => 'Индивидуальная печать, быстрые сроки, стабильное качество - комплексное B2B решение',
                    'cta1' => ['text' => 'Изучить продукты', 'href' => '/ru/products'],
                    'cta2' => ['text' => 'Связаться', 'href' => '/ru/contact'],
                ],
                [
                    'subtitle' => 'Широкий ассортимент',
                    'title' => '7 категорий, один поставщик',
                    'description' => 'Трубочки, стаканы, салфетки, влажные салфетки, зубочистки, сахар и стикеры - для Horeca',
                    'cta1' => ['text' => 'Категории', 'href' => '/ru/products'],
                    'cta2' => ['text' => 'Получить предложение', 'href' => '/ru/get-quote'],
                ],
                [
                    'subtitle' => 'Быстро и надежно',
                    'title' => 'Предложение за 24 часа, доставка за 20 дней',
                    'description' => 'Быстрый ответ, гибкое планирование, надежная поставка',
                    'cta1' => ['text' => 'Запросить предложение', 'href' => '/ru/get-quote'],
                    'cta2' => ['text' => 'Услуги', 'href' => '/ru/services'],
                ],
            ];
        } elseif ($lang === 'ar') {
            $heroSlides = [
                [
                    'subtitle' => 'حلول صناعية',
                    'title' => 'الشركة الرائدة في تصنيع ماصات الفروزن البلاستيكية في تركيا',
                    'description' => 'طباعة مخصصة، تسليم سريع، جودة مستقرة - حل B2B الشامل',
                    'cta1' => ['text' => 'استكشف المنتجات', 'href' => '/ar/products'],
                    'cta2' => ['text' => 'اتصل بنا', 'href' => '/ar/contact'],
                ],
                [
                    'subtitle' => 'مجموعة واسعة',
                    'title' => '7 فئات، مورد واحد',
                    'description' => 'ماصات، أكواب، مناديل، مناديل مبللة، أعواد أسنان، سكر وطباعة ستيكر - لقطاع هوريكا',
                    'cta1' => ['text' => 'الفئات', 'href' => '/ar/products'],
                    'cta2' => ['text' => 'احصل على عرض', 'href' => '/ar/get-quote'],
                ],
                [
                    'subtitle' => 'سريع وموثوق',
                    'title' => 'عرض سعر في 24 ساعة، تسليم في 20 يومًا',
                    'description' => 'استجابة سريعة، تخطيط مرن، توريد موثوق',
                    'cta1' => ['text' => 'اطلب عرضًا', 'href' => '/ar/get-quote'],
                    'cta2' => ['text' => 'الخدمات', 'href' => '/ar/services'],
                ],
            ];
        } else {
            $heroSlides = [
                [
                    'subtitle' => 'Industrial Solutions',
                    'title' => 'Turkey\'s Leading Plastic Frozen Straw Manufacturer',
                    'description' => 'Custom printing, fast turnaround, stable quality - your one-stop B2B solution',
                    'cta1' => ['text' => 'Explore Products', 'href' => '/en/products'],
                    'cta2' => ['text' => 'Contact Us', 'href' => '/en/contact'],
                ],
                [
                    'subtitle' => 'Wide Product Range',
                    'title' => '7 Categories, One Supplier',
                    'description' => 'Straws, cups, napkins, wet wipes, toothpicks, stick sugar and sticker printing - for Horeca segment',
                    'cta1' => ['text' => 'View Categories', 'href' => '/en/products'],
                    'cta2' => ['text' => 'Get Quote', 'href' => '/en/get-quote'],
                ],
                [
                    'subtitle' => 'Fast and Reliable',
                    'title' => 'Quote in 24 Hours, Delivery in 20 Days',
                    'description' => 'Fast response, flexible planning, reliable supply - focus on your business',
                    'cta1' => ['text' => 'Request Quote', 'href' => '/en/get-quote'],
                    'cta2' => ['text' => 'Our Services', 'href' => '/en/services'],
                ],
            ];
        }

        // Statistics Counter
        if ($lang === 'tr') {
            $statistics = [
                ['number' => 10, 'suffix' => 'M+', 'label' => 'Yıllık Üretim Kapasitesi'],
                ['number' => 50, 'suffix' => '+', 'label' => 'İhracat Ülkesi'],
                ['number' => 500, 'suffix' => '+', 'label' => 'Aktif Müşteri'],
                ['number' => 24, 'suffix' => '/7', 'label' => 'Destek Hattı'],
            ];
        } elseif ($lang === 'ru') {
            $statistics = [
                ['number' => 10, 'suffix' => 'М+', 'label' => 'Годовая мощность производства'],
                ['number' => 50, 'suffix' => '+', 'label' => 'Страны экспорта'],
                ['number' => 500, 'suffix' => '+', 'label' => 'Активные клиенты'],
                ['number' => 24, 'suffix' => '/7', 'label' => 'Поддержка'],
            ];
        } elseif ($lang === 'ar') {
            $statistics = [
                ['number' => 10, 'suffix' => 'م+', 'label' => 'طاقة إنتاج سنوية'],
                ['number' => 50, 'suffix' => '+', 'label' => 'دول التصدير'],
                ['number' => 500, 'suffix' => '+', 'label' => 'العملاء النشطون'],
                ['number' => 24, 'suffix' => '/7', 'label' => 'الدعم'],
            ];
        } else {
            $statistics = [
                ['number' => 10, 'suffix' => 'M+', 'label' => 'Annual Production Capacity'],
                ['number' => 50, 'suffix' => '+', 'label' => 'Export Countries'],
                ['number' => 500, 'suffix' => '+', 'label' => 'Active Clients'],
                ['number' => 24, 'suffix' => '/7', 'label' => 'Support Line'],
            ];
        }

        // USP Cards (Updated with metrics)
        if ($lang === 'tr') {
             $uspCards = [
                '10M+ Yıllık Üretim | Stok Garantisi',
                '24 Saat İçinde Teklif Yanıtı',
                '20 Gün Ortalama Termin',
                'ISO 9001 Sertifikalı Üretim',
            ];
        } elseif ($lang === 'ru') {
             $uspCards = [
                '10M+ Годовое Производство | Гарантия Запасов',
                'Ответ на предложение за 24 часа',
                'Средний срок доставки 20 дней',
                'Сертифицированное производство ISO 9001',
            ];
        } elseif ($lang === 'ar') {
             $uspCards = [
                '10 مليون+ إنتاج سنوي | ضمان المخزون',
                'رد العرض في 24 ساعة',
                'متوسط ​​مدة التسليم 20 يومًا',
                'إنتاج معتمد ISO 9001',
            ];
        } else {
            $uspCards = [
                '10M+ Annual Production | Stock Guarantee',
                'Quote Response in 24 Hours',
                '20-Day Average Lead Time',
                'ISO 9001 Certified Production',
            ];
        }

        // Solution Cards
        // Ideally this should be in a specialized Solution model or Config, but complying with existing structure
        if ($lang === 'tr') {
             $solutionCards = [
                ['title' => 'Kafe ve Kahve Zincirleri', 'body' => 'Pipet, bardak, peçete ve stick şeker setleriyle marka bütünlüğü.'],
                ['title' => 'Fast-food', 'body' => 'Yüksek sirkülasyona uygun standart ölçü ve planlı sevkiyat modeli.'],
                ['title' => 'Otel ve Catering', 'body' => 'Tekli ıslak mendil, bayraklı kürdan ve baskılı servis sarf ürünleri.'],
                ['title' => 'Etkinlik ve Organizasyon', 'body' => 'Kampanya dönemlerine uygun hızlı ürünlendirme ve teslim planlaması.'],
            ];
        } elseif ($lang === 'ru') {
            $solutionCards = [
                ['title' => 'Кафе и кофейные сети', 'body' => 'Целостность бренда с наборами трубочек, стаканов, салфеток и сахара.'],
                ['title' => 'Фаст-фуд', 'body' => 'Стандартные размеры и плановая модель отгрузки для высокой проходимости.'],
                ['title' => 'Отели и кейтеринг', 'body' => 'Влажные салфетки, зубочистки с флажками и печатные расходные материалы.'],
                ['title' => 'Мероприятия и организация', 'body' => 'Быстрое формирование ассортимента и планирование доставки для акций.'],
            ];
        } elseif ($lang === 'ar') {
            $solutionCards = [
                ['title' => 'المقاهي وسلاسل القهوة', 'body' => 'اتساق العلامة التجارية مع مجموعات الماصات والأكواب والمناديل والسكر.'],
                ['title' => 'الوجبات السريعة', 'body' => 'أبعاد قياسية ونموذج شحن مخطط للخدمة عالية الكثافة.'],
                ['title' => 'الفنادق والتتموين', 'body' => 'مناديل مبللة مفردة، أعواد أسنان بأعلام ومستهلكات مطبوعة.'],
                ['title' => 'الفعاليات والتنظيم', 'body' => 'تجهيز سريع للمنتجات وتخطيط التسليم لفترات الحملات.'],
            ];
        } else {
             $solutionCards = [
                ['title' => 'Cafe and Coffee Chains', 'body' => 'Brand consistency with straws, cups, napkins and stick sugar bundles.'],
                ['title' => 'Fast-food', 'body' => 'Standard dimensions and planned shipment model for high-volume service.'],
                ['title' => 'Hotels and Catering', 'body' => 'Single sachet wipes, flag toothpicks and printed consumables for premium service.'],
                ['title' => 'Events and Organizations', 'body' => 'Fast productization and delivery planning for campaign periods.'],
            ];
        }

        $categoryImages = [
            'pipet' => 'images/category-straws.svg',
            'straws' => 'images/category-straws.svg',
            'bardak' => 'images/category-cups.svg',
            'cups' => 'images/category-cups.svg',
            'pecete' => 'images/category-napkins.svg',
            'napkins' => 'images/category-napkins.svg',
            'islak-mendil' => 'images/category-wipes.svg',
            'wet-wipes' => 'images/category-wipes.svg',
            'bayrakli-kurdan' => 'images/category-toothpick.svg',
            'flag-toothpicks' => 'images/category-toothpick.svg',
            'stick-seker' => 'images/category-sugar.svg',
            'stick-sugar' => 'images/category-sugar.svg',
        ];

        $jsonLd = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => $companyName,
                'url' => rtrim(config('app.url'), '/'),
                'telephone' => $setting?->phone,
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'LocalBusiness',
                'name' => $companyName,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $setting?->address,
                    'addressCountry' => 'TR',
                ],
                'telephone' => $setting?->phone,
                'openingHours' => $setting?->working_hours,
            ],
        ];

        // Testimonials
        $testimonials = Cache::remember("home:{$lang}:testimonials", $cacheTtl, function () {
            return Testimonial::query()
                ->where('is_active', true)
                ->with('translations')
                ->orderBy('order')
                ->get();
        });

        return view('home', [
            'services' => $services,
            'categories' => $categories,
            'categoryImages' => $categoryImages,
            'heroSlides' => $heroSlides,
            'statistics' => $statistics,
            'uspCards' => $uspCards,
            'solutionCards' => $solutionCards,
            'products' => $products,
            'posts' => $posts,
            'testimonials' => $testimonials,
            'heroTitle' => $heroTitle,
            'heroSubtitle' => $heroSubtitle,
            'minOrderDefault' => $setting?->min_order_default ?: 5000,
            'seo' => $this->seo(
                $heroSlides[0]['title'] . ' | ' . $companyName,
                $heroSlides[0]['description'],
                $canonical,
                $alternates,
                $jsonLd,
            ),
        ]);
    }
}
