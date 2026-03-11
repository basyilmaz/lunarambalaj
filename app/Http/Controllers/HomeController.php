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

        $setting = Cache::remember('site:settings:first', $cacheTtl, function () {
            return Setting::query()->first();
        });

        $canonical = LocaleUrls::abs($lang === 'tr' ? '/' : '/' . $lang);
        $alternates = [
            'tr-TR' => LocaleUrls::abs('/'),
            'en' => LocaleUrls::abs('/en'),
            'ru' => LocaleUrls::abs('/ru'),
            'ar' => LocaleUrls::abs('/ar'),
            'es' => LocaleUrls::abs('/es'),
            'x-default' => LocaleUrls::abs('/'),
        ];

        $heroTitle = $setting?->{'hero_h1_' . $lang} ?: __('site.hero.title');
        $heroSubtitle = $setting?->{'hero_subtitle_' . $lang} ?: __('site.hero.subtitle');
        $companyName = $setting?->{'company_name_' . $lang} ?: __('site.brand');

        $heroSlidesByLocale = [
            'tr' => [
                [
                    'subtitle' => 'Endüstriyel Çözümler',
                    'title' => 'Türkiye\'nin Plastik Frozen Pipet Üretim Lideri',
                    'description' => 'Özel baskı, hızlı termin, stabil kalite. B2B operasyonlarınız için tek üretim merkezi.',
                    'cta1' => ['text' => 'Ürünleri Keşfedin', 'href' => '/urunler'],
                    'cta2' => ['text' => 'İletişime Geçin', 'href' => '/iletisim'],
                ],
                [
                    'subtitle' => 'Geniş Ürün Yelpazesi',
                    'title' => '7 Kategori, Tek Tedarikçi',
                    'description' => 'Pipet, bardak, peçete, ıslak mendil, bayraklı kürdan, stick şeker ve sticker baskı çözümleri.',
                    'cta1' => ['text' => 'Kategorileri Gör', 'href' => '/urunler'],
                    'cta2' => ['text' => 'Teklif Al', 'href' => '/teklif-al'],
                ],
                [
                    'subtitle' => 'Hızlı ve Güvenilir',
                    'title' => '24 Saatte Teklif, 20 Günde Termin',
                    'description' => 'Hızlı yanıt, planlı üretim ve kontrollü sevkiyat ile sürdürülebilir tedarik akışı.',
                    'cta1' => ['text' => 'Hemen Teklif Al', 'href' => '/teklif-al'],
                    'cta2' => ['text' => 'Hizmetlerimiz', 'href' => '/hizmetler'],
                ],
            ],
            'en' => [
                [
                    'subtitle' => 'Industrial Solutions',
                    'title' => 'Turkey\'s Leading Plastic Frozen Straw Manufacturer',
                    'description' => 'Custom printing, predictable lead times and stable quality for B2B supply operations.',
                    'cta1' => ['text' => 'Explore Products', 'href' => '/en/products'],
                    'cta2' => ['text' => 'Contact Us', 'href' => '/en/contact'],
                ],
                [
                    'subtitle' => 'Wide Product Range',
                    'title' => '7 Categories, One Supplier',
                    'description' => 'Straws, cups, napkins, wet wipes, flag toothpicks, stick sugar and sticker printing.',
                    'cta1' => ['text' => 'View Categories', 'href' => '/en/products'],
                    'cta2' => ['text' => 'Get Quote', 'href' => '/en/get-quote'],
                ],
                [
                    'subtitle' => 'Fast and Reliable',
                    'title' => 'Quote in 24 Hours, Lead Time in 20 Days',
                    'description' => 'Fast response, planned production and controlled dispatch for consistent supply.',
                    'cta1' => ['text' => 'Request Quote', 'href' => '/en/get-quote'],
                    'cta2' => ['text' => 'Our Services', 'href' => '/en/services'],
                ],
            ],
            'ru' => [
                [
                    'subtitle' => 'Промышленные решения',
                    'title' => 'Лидер Турции по производству пластиковых трубочек Frozen',
                    'description' => 'Индивидуальная печать, прогнозируемые сроки и стабильное качество для B2B.',
                    'cta1' => ['text' => 'Смотреть продукцию', 'href' => '/ru/products'],
                    'cta2' => ['text' => 'Связаться', 'href' => '/ru/contact'],
                ],
                [
                    'subtitle' => 'Широкая линейка',
                    'title' => '7 категорий, один поставщик',
                    'description' => 'Трубочки, стаканы, салфетки, влажные салфетки, зубочистки с флажком, stick sugar и стикеры.',
                    'cta1' => ['text' => 'Категории', 'href' => '/ru/products'],
                    'cta2' => ['text' => 'Получить расчет', 'href' => '/ru/get-quote'],
                ],
                [
                    'subtitle' => 'Быстро и надежно',
                    'title' => 'Расчет за 24 часа, срок 20 дней',
                    'description' => 'Оперативная обратная связь, плановое производство и контролируемая отгрузка.',
                    'cta1' => ['text' => 'Запросить расчет', 'href' => '/ru/get-quote'],
                    'cta2' => ['text' => 'Наши услуги', 'href' => '/ru/services'],
                ],
            ],
            'ar' => [
                [
                    'subtitle' => 'حلول صناعية',
                    'title' => 'الشركة الرائدة في تركيا لإنتاج مصاصات Frozen البلاستيكية',
                    'description' => 'طباعة مخصصة، مهل واضحة، وجودة مستقرة لتوريد B2B.',
                    'cta1' => ['text' => 'استكشف المنتجات', 'href' => '/ar/products'],
                    'cta2' => ['text' => 'تواصل معنا', 'href' => '/ar/contact'],
                ],
                [
                    'subtitle' => 'مجموعة واسعة',
                    'title' => '7 فئات، مورد واحد',
                    'description' => 'مصاصات، أكواب، مناديل، مناديل مبللة، كردان بالأعلام، سكر ساشيه وطباعة ستيكر.',
                    'cta1' => ['text' => 'عرض الفئات', 'href' => '/ar/products'],
                    'cta2' => ['text' => 'احصل على عرض', 'href' => '/ar/get-quote'],
                ],
                [
                    'subtitle' => 'سريع وموثوق',
                    'title' => 'عرض خلال 24 ساعة ومدة 20 يومًا',
                    'description' => 'استجابة سريعة، إنتاج مخطط، وشحن منضبط لسلسلة توريد مستقرة.',
                    'cta1' => ['text' => 'اطلب عرضًا', 'href' => '/ar/get-quote'],
                    'cta2' => ['text' => 'خدماتنا', 'href' => '/ar/services'],
                ],
            ],
            'es' => [
                [
                    'subtitle' => 'Soluciones Industriales',
                    'title' => 'Líder en Turquía en Producción de Pajitas Plásticas Frozen',
                    'description' => 'Impresión personalizada, plazos previsibles y calidad estable para operaciones B2B.',
                    'cta1' => ['text' => 'Explorar Productos', 'href' => '/es/products'],
                    'cta2' => ['text' => 'Contáctanos', 'href' => '/es/contact'],
                ],
                [
                    'subtitle' => 'Portafolio Amplio',
                    'title' => '7 Categorías, Un Solo Proveedor',
                    'description' => 'Pajitas, vasos, servilletas, toallitas húmedas, palillos con bandera, azúcar en stick e impresión de stickers.',
                    'cta1' => ['text' => 'Ver Categorías', 'href' => '/es/products'],
                    'cta2' => ['text' => 'Solicitar Cotización', 'href' => '/es/get-quote'],
                ],
                [
                    'subtitle' => 'Rápido y Confiable',
                    'title' => 'Cotización en 24 h, Plazo en 20 Días',
                    'description' => 'Respuesta ágil, producción planificada y despacho controlado para suministro continuo.',
                    'cta1' => ['text' => 'Pedir Cotización', 'href' => '/es/get-quote'],
                    'cta2' => ['text' => 'Nuestros Servicios', 'href' => '/es/services'],
                ],
            ],
        ];
        $heroSlides = $heroSlidesByLocale[$lang] ?? $heroSlidesByLocale['en'];

        $statisticsByLocale = [
            'tr' => [
                ['number' => 10, 'suffix' => 'M+', 'label' => 'Yıllık Üretim Kapasitesi'],
                ['number' => 50, 'suffix' => '+', 'label' => 'İhracat Ülkesi'],
                ['number' => 500, 'suffix' => '+', 'label' => 'Aktif Müşteri'],
                ['number' => 24, 'suffix' => '/7', 'label' => 'Destek Hattı'],
            ],
            'en' => [
                ['number' => 10, 'suffix' => 'M+', 'label' => 'Annual Production Capacity'],
                ['number' => 50, 'suffix' => '+', 'label' => 'Export Countries'],
                ['number' => 500, 'suffix' => '+', 'label' => 'Active Clients'],
                ['number' => 24, 'suffix' => '/7', 'label' => 'Support Line'],
            ],
            'ru' => [
                ['number' => 10, 'suffix' => 'M+', 'label' => 'Годовая производственная мощность'],
                ['number' => 50, 'suffix' => '+', 'label' => 'Страны экспорта'],
                ['number' => 500, 'suffix' => '+', 'label' => 'Активные клиенты'],
                ['number' => 24, 'suffix' => '/7', 'label' => 'Линия поддержки'],
            ],
            'ar' => [
                ['number' => 10, 'suffix' => 'م+', 'label' => 'طاقة إنتاج سنوية'],
                ['number' => 50, 'suffix' => '+', 'label' => 'دول التصدير'],
                ['number' => 500, 'suffix' => '+', 'label' => 'عملاء نشطون'],
                ['number' => 24, 'suffix' => '/7', 'label' => 'خط الدعم'],
            ],
            'es' => [
                ['number' => 10, 'suffix' => 'M+', 'label' => 'Capacidad de Producción Anual'],
                ['number' => 50, 'suffix' => '+', 'label' => 'Países de Exportación'],
                ['number' => 500, 'suffix' => '+', 'label' => 'Clientes Activos'],
                ['number' => 24, 'suffix' => '/7', 'label' => 'Línea de Soporte'],
            ],
        ];
        $statistics = $statisticsByLocale[$lang] ?? $statisticsByLocale['en'];

        $uspCardsByLocale = [
            'tr' => [
                '10M+ yıllık üretim kapasitesi',
                '24 saat içinde teklif yanıtı',
                '20 iş günü ortalama termin',
                'Stabil kalite kontrol süreci',
            ],
            'en' => [
                '10M+ annual production capacity',
                'Quotation response within 24 hours',
                '20 business days average lead time',
                'Stable quality control workflow',
            ],
            'ru' => [
                'Годовая мощность 10M+',
                'Ответ на запрос в течение 24 часов',
                'Средний срок 20 рабочих дней',
                'Стабильный контроль качества',
            ],
            'ar' => [
                'طاقة إنتاج سنوية 10M+',
                'رد على التسعير خلال 24 ساعة',
                'متوسط مدة 20 يوم عمل',
                'نظام جودة ثابت',
            ],
            'es' => [
                'Capacidad anual de producción 10M+',
                'Respuesta de cotización en 24 horas',
                'Plazo promedio de 20 días hábiles',
                'Flujo estable de control de calidad',
            ],
        ];
        $uspCards = $uspCardsByLocale[$lang] ?? $uspCardsByLocale['en'];

        $solutionCardsByLocale = [
            'tr' => [
                ['title' => 'Kafe ve Kahve Zincirleri', 'body' => 'Pipet, bardak, peçete ve stick şeker setleriyle marka bütünlüğü.'],
                ['title' => 'Fast-food', 'body' => 'Yüksek sirkülasyona uygun standart ölçü ve planlı sevkiyat modeli.'],
                ['title' => 'Otel ve Catering', 'body' => 'Tekli ıslak mendil, bayraklı kürdan ve baskılı servis sarf ürünleri.'],
                ['title' => 'Etkinlik ve Organizasyon', 'body' => 'Kampanya dönemlerine uygun hızlı ürünlendirme ve teslim planlaması.'],
            ],
            'en' => [
                ['title' => 'Cafe and Coffee Chains', 'body' => 'Brand consistency with straws, cups, napkins and stick sugar bundles.'],
                ['title' => 'Fast-food', 'body' => 'Standard dimensions and planned shipment model for high-volume service.'],
                ['title' => 'Hotels and Catering', 'body' => 'Single sachet wipes, flag toothpicks and printed consumables for premium service.'],
                ['title' => 'Events and Organizations', 'body' => 'Fast productization and delivery planning for campaign periods.'],
            ],
            'ru' => [
                ['title' => 'Кафе и кофейные сети', 'body' => 'Целостность бренда с наборами трубочек, стаканов, салфеток и stick sugar.'],
                ['title' => 'Fast-food', 'body' => 'Стандартные размеры и плановые отгрузки для интенсивного сервиса.'],
                ['title' => 'Отели и кейтеринг', 'body' => 'Индивидуальные влажные салфетки, флажковые зубочистки и печатные расходники.'],
                ['title' => 'Мероприятия', 'body' => 'Быстрое комплектование и планирование поставок под кампании.'],
            ],
            'ar' => [
                ['title' => 'المقاهي وسلاسل القهوة', 'body' => 'اتساق العلامة عبر مصاصات وأكواب ومناديل وسكر ساشيه.'],
                ['title' => 'الوجبات السريعة', 'body' => 'مقاسات قياسية وشحن مخطط لعمليات الخدمة السريعة.'],
                ['title' => 'الفنادق والتموين', 'body' => 'مناديل فردية وكردان بالأعلام ومنتجات خدمة مطبوعة.'],
                ['title' => 'الفعاليات', 'body' => 'تجهيز سريع وخطة تسليم مناسبة لفترات الحملات.'],
            ],
            'es' => [
                ['title' => 'Cafeterías y Cadenas de Café', 'body' => 'Consistencia de marca con sets de pajitas, vasos, servilletas y azúcar en stick.'],
                ['title' => 'Comida Rápida', 'body' => 'Medidas estándar y envíos planificados para operaciones de alto volumen.'],
                ['title' => 'Hoteles y Catering', 'body' => 'Toallitas individuales, palillos con bandera y consumibles impresos para servicio premium.'],
                ['title' => 'Eventos y Organización', 'body' => 'Preparación rápida de producto y planificación de entrega para campañas.'],
            ],
        ];
        $solutionCards = $solutionCardsByLocale[$lang] ?? $solutionCardsByLocale['en'];

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
