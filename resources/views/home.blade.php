@extends('layouts.app')

@section('content')
@php
    $locale = app()->getLocale();
    $homeUi = [
        'services_subtitle' => ['tr' => 'Neler Yapıyoruz', 'en' => 'What We Do', 'ru' => 'Что мы делаем', 'ar' => 'ماذا نقدم', 'es' => 'Qué Hacemos'],
        'solutions_subtitle' => ['tr' => 'Sektörel Çözümler', 'en' => 'Industry Solutions', 'ru' => 'Отраслевые решения', 'ar' => 'حلول حسب القطاع', 'es' => 'Soluciones por Sector'],
        'solutions_title' => ['tr' => 'İşletmenize Özel Paketler', 'en' => 'Tailored Packages for Your Business', 'ru' => 'Пакеты под ваш бизнес', 'ar' => 'باقات مخصصة لنشاطك', 'es' => 'Paquetes a Medida para tu Negocio'],
        'all_solutions' => ['tr' => 'Tüm Çözümleri Gör', 'en' => 'View All Solutions', 'ru' => 'Все решения', 'ar' => 'عرض كل الحلول', 'es' => 'Ver Todas las Soluciones'],
        'references_subtitle' => ['tr' => 'Referanslarımız', 'en' => 'Our References', 'ru' => 'Наши референсы', 'ar' => 'مراجعنا', 'es' => 'Nuestras Referencias'],
        'references_title' => ['tr' => 'Müşterilerimiz Ne Diyor?', 'en' => 'What Our Clients Say', 'ru' => 'Что говорят клиенты?', 'ar' => 'ماذا يقول عملاؤنا؟', 'es' => '¿Qué Dicen Nuestros Clientes?'],
        'lifestyle_subtitle' => ['tr' => 'Ürünlerimiz Hayatınızda', 'en' => 'Our Products in Your Life', 'ru' => 'Наши продукты в вашей жизни', 'ar' => 'منتجاتنا في حياتكم', 'es' => 'Nuestros Productos en tu Día a Día'],
        'lifestyle_title' => ['tr' => 'Kalite ve Tasarım Bir Arada', 'en' => 'Quality Meets Design', 'ru' => 'Качество и дизайн вместе', 'ar' => 'الجودة والتصميم معًا', 'es' => 'Calidad y Diseño en Conjunto'],
        'corporate_print' => ['tr' => 'Kurumsal Baskı', 'en' => 'Corporate Printing', 'ru' => 'Корпоративная печать', 'ar' => 'طباعة مؤسسية', 'es' => 'Impresión Corporativa'],
        'corporate_print_desc' => ['tr' => 'Logonuz ve markanız tüm ürünlerde', 'en' => 'Your logo and brand on all products', 'ru' => 'Ваш логотип и бренд на всей продукции', 'ar' => 'شعاركم وعلامتكم على كل المنتجات', 'es' => 'Tu logotipo y marca en todos los productos'],
        'fast_production' => ['tr' => 'Hızlı Üretim', 'en' => 'Fast Production', 'ru' => 'Быстрое производство', 'ar' => 'إنتاج سريع', 'es' => 'Producción Rápida'],
        'fast_production_desc' => ['tr' => '20 gün ortalama termin süresi', 'en' => '20-day average lead time', 'ru' => 'Средний срок: 20 дней', 'ar' => 'متوسط مدة التنفيذ: 20 يومًا', 'es' => 'Plazo promedio de 20 días'],
        'quality_guarantee' => ['tr' => 'Kalite Garantisi', 'en' => 'Quality Guarantee', 'ru' => 'Гарантия качества', 'ar' => 'ضمان الجودة', 'es' => 'Garantía de Calidad'],
        'quality_guarantee_desc' => ['tr' => 'Stabil kalite kontrol süreci', 'en' => 'Stable quality control workflow', 'ru' => 'Стабильный контроль качества', 'ar' => 'نظام جودة ثابت', 'es' => 'Control de calidad estable'],
        'explore_products' => ['tr' => 'Tüm Ürünleri Keşfedin', 'en' => 'Explore All Products', 'ru' => 'Смотреть все продукты', 'ar' => 'استكشف كل المنتجات', 'es' => 'Explorar Todos los Productos'],
        'blog_subtitle' => ['tr' => 'Blog ve Haberler', 'en' => 'Blog & News', 'ru' => 'Блог и новости', 'ar' => 'المدونة والأخبار', 'es' => 'Blog y Novedades'],
        'blog_title' => ['tr' => 'Son Yazılar', 'en' => 'Latest Articles', 'ru' => 'Последние статьи', 'ar' => 'أحدث المقالات', 'es' => 'Últimos Artículos'],
        'all_posts' => ['tr' => 'Tüm Yazıları Gör', 'en' => 'View All Articles', 'ru' => 'Смотреть все статьи', 'ar' => 'عرض كل المقالات', 'es' => 'Ver Todos los Artículos'],
        'cta_title' => ['tr' => 'Ambalaj İhtiyacınız İçin Hemen Teklif Alın', 'en' => 'Get Your Quote for Packaging Needs Now', 'ru' => 'Получите расчет для ваших упаковочных задач', 'ar' => 'احصل على عرض سعر لاحتياجات التعبئة الآن', 'es' => 'Solicita Ahora tu Cotización de Empaque'],
        'cta_desc' => ['tr' => '24 saat içinde özel fiyat teklifi ve hızlı termin bilgisi', 'en' => 'Get custom pricing and fast lead time information within 24 hours', 'ru' => 'Индивидуальный расчет и сроки в течение 24 часов', 'ar' => 'سعر مخصص ومعلومات المدة خلال 24 ساعة', 'es' => 'Precio personalizado y plazo en 24 horas'],
    ];

    $leadPanel = [
        'subtitle' => ['tr' => 'Satın Alma Özeti', 'en' => 'Procurement Snapshot', 'ru' => 'Сводка закупки', 'ar' => 'ملخص الشراء', 'es' => 'Resumen de Compra'],
        'title' => ['tr' => 'Tekliften Teslime Net Operasyon Çerçevesi', 'en' => 'Clear Flow from Quote to Delivery', 'ru' => 'Понятный процесс от расчета до поставки', 'ar' => 'مسار واضح من العرض حتى التسليم', 'es' => 'Flujo Claro de Cotizacion a Entrega'],
        'cards' => [
            [
                'value' => ['tr' => '24 Saat', 'en' => '24 Hours', 'ru' => '24 часа', 'ar' => '24 ساعة', 'es' => '24 Horas'],
                'title' => ['tr' => 'Teklif Dönüşü', 'en' => 'Quote SLA', 'ru' => 'SLA расчета', 'ar' => 'زمن عرض السعر', 'es' => 'SLA de Cotizacion'],
                'desc' => ['tr' => 'Kategori, adet ve baskı detayına göre hızlı fiyat dönüşü.', 'en' => 'Fast response based on category, volume and print scope.', 'ru' => 'Быстрый расчет по категории, объему и печати.', 'ar' => 'رد سريع حسب الفئة والكمية ونطاق الطباعة.', 'es' => 'Respuesta rapida segun categoria, volumen y alcance de impresion.'],
            ],
            [
                'value' => ['tr' => '20 Gün', 'en' => '20 Days', 'ru' => '20 дней', 'ar' => '20 يومًا', 'es' => '20 Dias'],
                'title' => ['tr' => 'Planlı Termin', 'en' => 'Planned Lead Time', 'ru' => 'Плановый срок', 'ar' => 'مدة تنفيذ مخططة', 'es' => 'Plazo Planificado'],
                'desc' => ['tr' => 'Ürün grubuna göre planlanan standart üretim ve sevkiyat akışı.', 'en' => 'Standard production and dispatch flow planned by product group.', 'ru' => 'Стандартный план производства и отгрузки по продукту.', 'ar' => 'خطة إنتاج وشحن قياسية حسب مجموعة المنتج.', 'es' => 'Flujo estandar de produccion y despacho por categoria.'],
            ],
            [
                'value' => ['tr' => 'Min. 5.000', 'en' => 'Min. 5,000', 'ru' => 'Мин. 5 000', 'ar' => 'حد أدنى 5,000', 'es' => 'Min. 5.000'],
                'title' => ['tr' => 'MOQ Yönetimi', 'en' => 'MOQ Planning', 'ru' => 'План MOQ', 'ar' => 'تخطيط الحد الأدنى', 'es' => 'Plan de MOQ'],
                'desc' => ['tr' => 'Ürün bazında değişen minimum adetlerle dengeli satın alma planı.', 'en' => 'Balanced planning with product-based minimum quantities.', 'ru' => 'Сбалансированная закупка с учетом MOQ по продукту.', 'ar' => 'تخطيط شراء متوازن وفق الحد الأدنى لكل منتج.', 'es' => 'Plan de compra equilibrado con minimos por producto.'],
            ],
            [
                'value' => ['tr' => '5 Dil', 'en' => '5 Languages', 'ru' => '5 языков', 'ar' => '5 لغات', 'es' => '5 Idiomas'],
                'title' => ['tr' => 'İhracat Uyumlu İletişim', 'en' => 'Export-Ready Communication', 'ru' => 'Экспортная коммуникация', 'ar' => 'تواصل جاهز للتصدير', 'es' => 'Comunicacion para Exportacion'],
                'desc' => ['tr' => 'TR, EN, RU, AR ve ES içeriklerle çok pazarlı teklif akışı.', 'en' => 'Multi-market quotation flow with TR, EN, RU, AR and ES content.', 'ru' => 'Мультиязычный поток предложений: TR, EN, RU, AR, ES.', 'ar' => 'مسار عروض متعدد الأسواق بلغات TR وEN وRU وAR وES.', 'es' => 'Flujo comercial multilingue con contenido TR, EN, RU, AR y ES.'],
            ],
        ],
    ];
@endphp

<!-- Hero Slider -->
<section class="hero-swiper swiper relative">
    <div class="swiper-wrapper">
        @foreach($heroSlides as $slide)
            <div class="swiper-slide">
                <div class="relative bg-slate-900 overflow-hidden min-h-[560px] md:min-h-[700px] flex items-center">
                    <!-- Background Image -->
                    <div class="absolute inset-0">
                        <img
                            src="{{ asset('images/hero-bg.webp') }}"
                            alt="Lunar Ambalaj"
                            class="w-full h-full object-cover opacity-50"
                            width="1920"
                            height="1080"
                            loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                            fetchpriority="{{ $loop->first ? 'high' : 'low' }}"
                            decoding="async"
                        >
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/70 to-transparent"></div>
                    </div>

                    <!-- Content -->
                    <div class="relative max-w-7xl mx-auto px-4 w-full py-20">
                        <div class="max-w-3xl">
                            <div class="inline-block mb-6 px-4 py-1.5 border-l-4 border-primary-yellow bg-white/10 backdrop-blur text-white text-xs font-bold uppercase tracking-[0.2em]">
                                {{ $slide['subtitle'] }}
                            </div>

                            <h1 class="text-5xl md:text-7xl font-bold text-white mb-8 font-heading leading-none uppercase tracking-tight">
                                {{ $slide['title'] }}
                            </h1>

                            <p class="text-xl text-slate-300 mb-10 max-w-2xl font-light leading-relaxed">
                                {{ $slide['description'] }}
                            </p>

                            <div class="flex flex-wrap gap-5">
                                <x-button variant="primary" :href="$slide['cta1']['href']" size="lg">
                                    {{ $slide['cta1']['text'] }}
                                </x-button>

                                <x-button variant="outline" :href="$slide['cta2']['href']" size="lg" class="!border-slate-500 !text-white hover:!border-white hover:!bg-white/10">
                                    {{ $slide['cta2']['text'] }}
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Navigation -->
    <div class="swiper-button-next !text-primary-yellow"></div>
    <div class="swiper-button-prev !text-primary-yellow"></div>

    <!-- Pagination -->
    <div class="swiper-pagination !bottom-8"></div>
</section>

<!-- USP Cards -->
<section class="bg-slate-50 py-16 -mt-10 relative z-10">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($uspCards as $card)
                <article class="group bg-white p-8 shadow-sm border-b-4 border-transparent hover:border-primary-yellow transition-all duration-300 hover:-translate-y-1" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="mb-4 text-primary-yellow">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-800 uppercase tracking-wide leading-relaxed">{{ $card }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<!-- Statistics Counter -->
<x-stats-counter :stats="$statistics" />

<!-- Procurement Snapshot -->
<section class="bg-slate-900 py-20">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="$leadPanel['subtitle'][$locale] ?? $leadPanel['subtitle']['en']"
            :title="$leadPanel['title'][$locale] ?? $leadPanel['title']['en']"
            align="left"
            class="!text-white [&_h2]:!text-white [&_span]:!text-primary-yellow [&_p]:!text-slate-300"
        />

        <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach($leadPanel['cards'] as $card)
                <article class="border border-slate-700 bg-slate-800/60 p-6">
                    <p class="text-3xl font-bold text-primary-yellow">{{ $card['value'][$locale] ?? $card['value']['en'] }}</p>
                    <h3 class="mt-3 text-sm font-bold uppercase tracking-wide text-white">{{ $card['title'][$locale] ?? $card['title']['en'] }}</h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-300">{{ $card['desc'][$locale] ?? $card['desc']['en'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<!-- Product Categories -->
<section class="cv-auto py-24 bg-white">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="__('site.sections.products')"
            :title="__('site.home.categories_title')"
            align="left"
        />

        <div class="categories-swiper swiper mt-12">
            <div class="swiper-wrapper">
                @foreach($categories as $category)
                    @php
                        $t = $category->translation(app()->getLocale());
                        $slug = $t?->slug;
                        $image = $categoryImages[$slug] ?? 'images/category-straws.svg';
                    @endphp
                    @if($t)
                        <div class="swiper-slide">
                            <a href="{{ route(app()->getLocale() . '.products', ['category' => $slug]) }}"
                               class="group block relative h-80 overflow-hidden bg-slate-100 shadow-sm hover:shadow-xl transition-shadow">
                                <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-slate-900/0 transition-colors z-10"></div>
                                <img
                                    loading="lazy"
                                    src="{{ asset($image) }}"
                                    alt="{{ $t->name }}"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    width="640"
                                    height="640"
                                    decoding="async"
                                >
                                <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-slate-900/90 to-transparent z-20">
                                    <h3 class="text-2xl font-bold text-white font-heading uppercase mb-2">{{ $t->name }}</h3>
                                    <div class="w-12 h-1 bg-primary-yellow group-hover:w-full transition-all duration-500"></div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Navigation -->
            <div class="categories-prev absolute left-2 md:left-0 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-primary-yellow hover:bg-dark-charcoal text-dark-charcoal hover:text-white items-center justify-center cursor-pointer transition-colors hidden md:flex md:-ml-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </div>
            <div class="categories-next absolute right-2 md:right-0 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-primary-yellow hover:bg-dark-charcoal text-dark-charcoal hover:text-white items-center justify-center cursor-pointer transition-colors hidden md:flex md:-mr-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>

            <!-- Pagination -->
            <div class="categories-pagination mt-8 text-center"></div>
        </div>

        <div class="mt-12 text-center">
            <x-button variant="outline" :href="route(app()->getLocale() . '.products')">
                {{ __('site.sections.all_products') }}
                <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </x-button>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="cv-auto py-24 bg-dark-charcoal">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="$homeUi['services_subtitle'][$locale] ?? $homeUi['services_subtitle']['en']"
            :title="__('site.menu.services')"
            align="center"
            class="!text-white [&_h2]:!text-white [&_span]:!text-primary-yellow [&_p]:!text-light-gray"
        />

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 mt-12">
            @foreach($services->take(6) as $service)
                <x-card.service :service="$service" :locale="app()->getLocale()" />
            @endforeach
        </div>
    </div>
</section>

<!-- Solutions Section -->
<section class="cv-auto py-24 bg-white">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="$homeUi['solutions_subtitle'][$locale] ?? $homeUi['solutions_subtitle']['en']"
            :title="$homeUi['solutions_title'][$locale] ?? $homeUi['solutions_title']['en']"
            align="center"
        />

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4 mt-12">
            @foreach($solutionCards as $solution)
                <div class="group bg-slate-50 p-6 hover:bg-white hover:shadow-xl transition-all duration-300 border-l-4 border-transparent hover:border-primary-yellow" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <h3 class="text-xl font-bold text-slate-900 font-heading uppercase mb-3 group-hover:text-primary-yellow transition-colors">
                        {{ $solution['title'] }}
                    </h3>
                    <p class="text-text-gray text-sm leading-relaxed">
                        {{ $solution['body'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <x-button variant="primary" :href="route(app()->getLocale() . '.solutions')">
                {{ $homeUi['all_solutions'][$locale] ?? $homeUi['all_solutions']['en'] }}
            </x-button>
        </div>
    </div>
</section>

<!-- Testimonials Section (placeholder for when testimonials are added) -->
@if(count($testimonials) > 0)
<section class="cv-auto py-24 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="$homeUi['references_subtitle'][$locale] ?? $homeUi['references_subtitle']['en']"
            :title="$homeUi['references_title'][$locale] ?? $homeUi['references_title']['en']"
            align="center"
        />

        <div class="testimonials-swiper swiper mt-12">
            <div class="swiper-wrapper">
                @foreach($testimonials as $testimonial)
                    <div class="swiper-slide">
                        <x-testimonial :testimonial="$testimonial" />
                    </div>
                @endforeach
            </div>

            <!-- Navigation -->
            <div class="testimonials-prev absolute left-2 md:left-0 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-primary-yellow hover:bg-dark-charcoal text-dark-charcoal hover:text-white items-center justify-center cursor-pointer transition-colors hidden md:flex md:-ml-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </div>
            <div class="testimonials-next absolute right-2 md:right-0 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-primary-yellow hover:bg-dark-charcoal text-dark-charcoal hover:text-white items-center justify-center cursor-pointer transition-colors hidden md:flex md:-mr-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>

            <!-- Pagination -->
            <div class="testimonials-pagination mt-8 text-center"></div>
        </div>
    </div>
</section>
@endif

<!-- Lifestyle & Showcase Section -->
<section class="cv-auto py-24 bg-gradient-to-br from-slate-50 via-white to-slate-50">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="$homeUi['lifestyle_subtitle'][$locale] ?? $homeUi['lifestyle_subtitle']['en']"
            :title="$homeUi['lifestyle_title'][$locale] ?? $homeUi['lifestyle_title']['en']"
            align="center"
        />

        <div class="grid md:grid-cols-2 gap-8 mt-12">
            <!-- Left: Product Showcase -->
            <div class="grid grid-cols-2 gap-4" data-aos="fade-up">
                <div class="space-y-4">
                    <div class="overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-500 group">
                        <img src="{{ asset('images/product-cup-colorful.webp') }}" alt="Lunar Ambalaj Bardak" loading="lazy" fetchpriority="low" width="640" height="800" decoding="async" class="w-full h-auto object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-500 group">
                        <img src="{{ asset('images/lifestyle-cocktail.webp') }}" alt="Lunar Pipet" loading="lazy" fetchpriority="low" width="640" height="640" decoding="async" class="w-full h-auto object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                </div>
                <div class="space-y-4 pt-12">
                    <div class="overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-500 group">
                        <img src="{{ asset('images/product-kraft-bag.webp') }}" alt="Lunar Ambalaj Kraft Torba" loading="lazy" fetchpriority="low" width="640" height="800" decoding="async" class="w-full h-auto object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-500 group">
                        <img src="{{ asset('images/product-cup-white.webp') }}" alt="Lunar Bardak Beyaz" loading="lazy" fetchpriority="low" width="640" height="800" decoding="async" class="w-full h-auto object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                </div>
            </div>

            <!-- Right: Lifestyle Images & Text -->
            <div class="flex flex-col justify-center" data-aos="fade-up" data-aos-delay="100">
                <div class="mb-8 overflow-hidden rounded-lg shadow-2xl">
                    <img src="{{ asset('images/lifestyle-iced-coffee.webp') }}" alt="Lunar Ambalaj Ürünleri" loading="lazy" fetchpriority="low" width="900" height="1125" decoding="async" class="w-full h-auto object-cover">
                </div>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-yellow rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-dark-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 font-heading uppercase mb-1">
                                {{ $homeUi['corporate_print'][$locale] ?? $homeUi['corporate_print']['en'] }}
                            </h3>
                            <p class="text-text-gray text-sm">
                                {{ $homeUi['corporate_print_desc'][$locale] ?? $homeUi['corporate_print_desc']['en'] }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-yellow rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-dark-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 font-heading uppercase mb-1">
                                {{ $homeUi['fast_production'][$locale] ?? $homeUi['fast_production']['en'] }}
                            </h3>
                            <p class="text-text-gray text-sm">
                                {{ $homeUi['fast_production_desc'][$locale] ?? $homeUi['fast_production_desc']['en'] }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-yellow rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-dark-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 font-heading uppercase mb-1">
                                {{ $homeUi['quality_guarantee'][$locale] ?? $homeUi['quality_guarantee']['en'] }}
                            </h3>
                            <p class="text-text-gray text-sm">
                                {{ $homeUi['quality_guarantee_desc'][$locale] ?? $homeUi['quality_guarantee_desc']['en'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <x-button variant="primary" :href="route(app()->getLocale() . '.products')">
                        {{ $homeUi['explore_products'][$locale] ?? $homeUi['explore_products']['en'] }}
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section class="cv-auto py-24 bg-white">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="$homeUi['blog_subtitle'][$locale] ?? $homeUi['blog_subtitle']['en']"
            :title="$homeUi['blog_title'][$locale] ?? $homeUi['blog_title']['en']"
            align="center"
        />

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 mt-12">
            @foreach($posts as $post)
                <x-card.blog :post="$post" :locale="app()->getLocale()" />
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <x-button variant="outline" :href="route(app()->getLocale() . '.blog')">
                {{ $homeUi['all_posts'][$locale] ?? $homeUi['all_posts']['en'] }}
            </x-button>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="cv-auto py-20 bg-primary-yellow">
    <div class="mx-auto max-w-7xl px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-dark-charcoal font-heading uppercase mb-6" data-aos="fade-up">
            {{ $homeUi['cta_title'][$locale] ?? $homeUi['cta_title']['en'] }}
        </h2>

        <p class="text-lg text-dark-charcoal/80 mb-10 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            {{ $homeUi['cta_desc'][$locale] ?? $homeUi['cta_desc']['en'] }}
        </p>

        <div class="flex flex-wrap gap-5 justify-center" data-aos="fade-up" data-aos-delay="200">
            <x-button variant="secondary" :href="route(app()->getLocale() . '.quote')" size="lg">
                {{ __('site.cta_quote') }}
            </x-button>

            <x-button variant="ghost" :href="route(app()->getLocale() . '.contact')" size="lg" class="!text-dark-charcoal hover:!text-white hover:!bg-dark-charcoal/10">
                {{ __('site.menu.contact') }}
            </x-button>
        </div>
    </div>
</section>

@endsection
