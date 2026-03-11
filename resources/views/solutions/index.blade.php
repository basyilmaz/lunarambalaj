@extends('layouts.app')

@section('content')
@php
    $locale = app()->getLocale();
    $ui = [
        'hero_badge' => ['tr' => 'Sektöre Özel', 'en' => 'Industry Solutions', 'ru' => 'Отраслевой фокус', 'ar' => 'حلول قطاعية', 'es' => 'Soluciones por Sector'],
        'hero_title' => ['tr' => 'Sektöre Göre Çözümler', 'en' => 'Solutions by Segment', 'ru' => 'Решения по сегментам', 'ar' => 'حلول حسب القطاع', 'es' => 'Soluciones por Segmento'],
        'hero_desc' => [
            'tr' => 'Kafe, otel, fast-food, catering ve etkinlik operasyonları için ürün kombinasyonlarını tek tedarik modeliyle planlıyoruz.',
            'en' => 'We plan product bundles for cafes, hotels, fast-food, catering and event operations with a single-supplier model.',
            'ru' => 'Планируем продуктовые наборы для кафе, отелей, fast-food, кейтеринга и мероприятий по модели единого поставщика.',
            'ar' => 'نخطط مجموعات المنتجات للمقاهي والفنادق والوجبات السريعة والتموين والفعاليات بنموذج المورد الواحد.',
            'es' => 'Planificamos combinaciones de producto para cafeterías, hoteles, fast-food, catering y eventos con modelo de proveedor único.',
        ],
        'set_label' => ['tr' => 'Önerilen Set:', 'en' => 'Suggested Set:', 'ru' => 'Рекомендуемый набор:', 'ar' => 'الطقم المقترح:', 'es' => 'Set Sugerido:'],
        'cta_fast_quote' => ['tr' => 'Hızlı Teklif Al', 'en' => 'Get Fast Quote', 'ru' => 'Быстрый расчет', 'ar' => 'احصل على عرض سريع', 'es' => 'Cotización Rápida'],
        'process_badge' => ['tr' => 'Süreç', 'en' => 'Process', 'ru' => 'Процесс', 'ar' => 'العملية', 'es' => 'Proceso'],
        'process_title' => ['tr' => 'Nasıl Çalışıyoruz?', 'en' => 'How We Work', 'ru' => 'Как мы работаем?', 'ar' => 'كيف نعمل؟', 'es' => '¿Cómo Trabajamos?'],
        'final_title' => ['tr' => 'Özel Çözüm Paketi Hazırlayalım', 'en' => 'Let’s Prepare a Custom Solution Package', 'ru' => 'Подготовим индивидуальный пакет решений', 'ar' => 'لنجهّز باقة حلول مخصصة', 'es' => 'Preparemos un Paquete de Solución a Medida'],
        'final_desc' => [
            'tr' => 'Proje bazlı taleplerde numune, termin ve sevkiyat planlaması ile ilerliyoruz. Kategori setinizi belirtin, 24 saat içinde detaylı teklif alın.',
            'en' => 'Project-based requests are handled with sampling, lead-time and shipment planning. Specify your category set and get a detailed quote within 24 hours.',
            'ru' => 'Для проектных запросов работаем с образцами, сроками и планом отгрузки. Укажите категории и получите расчет за 24 часа.',
            'ar' => 'في الطلبات القائمة على المشاريع نعمل عبر العينات والمدة وخطة الشحن. حدّد الفئات واحصل على عرض خلال 24 ساعة.',
            'es' => 'En solicitudes por proyecto avanzamos con muestra, plazo y planificación de envío. Comparte tus categorías y recibe cotización en 24 horas.',
        ],
        'final_quote' => ['tr' => '24 Saatte Teklif Alın', 'en' => 'Get Quote in 24h', 'ru' => 'Расчет за 24 часа', 'ar' => 'عرض خلال 24 ساعة', 'es' => 'Cotización en 24 h'],
        'final_products' => ['tr' => 'Ürünleri İncele', 'en' => 'Browse Products', 'ru' => 'Смотреть продукты', 'ar' => 'استعرض المنتجات', 'es' => 'Ver Productos'],
    ];

    $steps = [
        [
            'num' => '01',
            'title' => ['tr' => 'İhtiyaç Analizi', 'en' => 'Requirement Analysis', 'ru' => 'Анализ потребности', 'ar' => 'تحليل الاحتياج', 'es' => 'Análisis de Necesidad'],
            'desc' => ['tr' => 'Kategori, adet ve kullanım senaryonuzu değerlendiriyoruz.', 'en' => 'We evaluate your category, quantity and usage scenario.', 'ru' => 'Оцениваем категорию, объем и сценарий использования.', 'ar' => 'نقيّم الفئة والكمية وسيناريو الاستخدام.', 'es' => 'Evaluamos categoría, cantidad y escenario de uso.'],
        ],
        [
            'num' => '02',
            'title' => ['tr' => 'Numune / Onay', 'en' => 'Sampling / Approval', 'ru' => 'Образец / Подтверждение', 'ar' => 'العينة / الاعتماد', 'es' => 'Muestra / Aprobación'],
            'desc' => ['tr' => 'Baskı provaları ve ürün numuneleriyle önizleme süreci.', 'en' => 'Preview with print proofs and product samples.', 'ru' => 'Предпросмотр через print-proof и образцы продукции.', 'ar' => 'مرحلة معاينة عبر بروفات الطباعة وعينات المنتج.', 'es' => 'Previsualización con pruebas de impresión y muestras de producto.'],
        ],
        [
            'num' => '03',
            'title' => ['tr' => 'Üretim Planlama', 'en' => 'Production Planning', 'ru' => 'Планирование производства', 'ar' => 'تخطيط الإنتاج', 'es' => 'Planificación de Producción'],
            'desc' => ['tr' => 'Termin ve sevkiyat takvimini netleştiriyoruz.', 'en' => 'We finalize lead time and shipment calendar.', 'ru' => 'Подтверждаем сроки и календарь отгрузки.', 'ar' => 'نؤكد مدة التنفيذ وجدول الشحن.', 'es' => 'Definimos plazo y calendario de envío.'],
        ],
        [
            'num' => '04',
            'title' => ['tr' => 'Sevkiyat ve Takip', 'en' => 'Shipment & Follow-up', 'ru' => 'Отгрузка и сопровождение', 'ar' => 'الشحن والمتابعة', 'es' => 'Envío y Seguimiento'],
            'desc' => ['tr' => 'Teslimat sonrası destek ve stok yönetimi.', 'en' => 'Post-delivery support and stock continuity.', 'ru' => 'Поддержка после поставки и управление запасом.', 'ar' => 'دعم ما بعد التسليم واستمرارية المخزون.', 'es' => 'Soporte post-entrega y continuidad de stock.'],
        ],
    ];

    $opsCards = [
        [
            'value' => ['tr' => '24 Saat', 'en' => '24 Hours', 'ru' => '24 часа', 'ar' => '24 ساعة', 'es' => '24 Horas'],
            'title' => ['tr' => 'Teklif Yanıt Süresi', 'en' => 'Quote Response SLA', 'ru' => 'SLA расчета', 'ar' => 'زمن عرض السعر', 'es' => 'SLA de Cotizacion'],
        ],
        [
            'value' => ['tr' => '20 Gün', 'en' => '20 Days', 'ru' => '20 дней', 'ar' => '20 يومًا', 'es' => '20 Dias'],
            'title' => ['tr' => 'Standart Termin', 'en' => 'Standard Lead Time', 'ru' => 'Стандартный срок', 'ar' => 'مدة قياسية', 'es' => 'Plazo Estandar'],
        ],
        [
            'value' => ['tr' => 'MOQ', 'en' => 'MOQ', 'ru' => 'MOQ', 'ar' => 'MOQ', 'es' => 'MOQ'],
            'title' => ['tr' => 'Ürün Bazlı Planlama', 'en' => 'Product-Based Planning', 'ru' => 'План по продукту', 'ar' => 'تخطيط حسب المنتج', 'es' => 'Plan por Producto'],
        ],
        [
            'value' => ['tr' => '5 Dil', 'en' => '5 Languages', 'ru' => '5 языков', 'ar' => '5 لغات', 'es' => '5 Idiomas'],
            'title' => ['tr' => 'Çok Pazarlı İletişim', 'en' => 'Multi-Market Communication', 'ru' => 'Мультиязычная коммуникация', 'ar' => 'تواصل متعدد الأسواق', 'es' => 'Comunicacion Multi-Mercado'],
        ],
    ];
@endphp

<section class="relative flex min-h-[450px] items-center overflow-hidden bg-gradient-to-br from-primary-yellow via-amber-400 to-yellow-500">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23000&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="relative mx-auto w-full max-w-7xl px-4 py-20">
        <div class="mb-4 inline-block border-l-4 border-dark-charcoal bg-dark-charcoal/10 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-dark-charcoal">
            {{ $ui['hero_badge'][$locale] ?? $ui['hero_badge']['en'] }}
        </div>
        <h1 class="mb-6 font-heading text-4xl font-bold leading-tight text-dark-charcoal md:text-5xl">
            {{ $ui['hero_title'][$locale] ?? $ui['hero_title']['en'] }}
        </h1>
        <p class="max-w-3xl text-xl font-light leading-relaxed text-dark-charcoal/80">
            {{ $ui['hero_desc'][$locale] ?? $ui['hero_desc']['en'] }}
        </p>
    </div>
</section>

<section class="bg-dark-charcoal py-10">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach($opsCards as $card)
                <article class="border border-slate-700 bg-slate-800/60 p-4">
                    <p class="text-2xl font-bold text-primary-yellow">{{ $card['value'][$locale] ?? $card['value']['en'] }}</p>
                    <p class="mt-2 text-xs font-bold uppercase tracking-wide text-white">{{ $card['title'][$locale] ?? $card['title']['en'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-slate-50 py-16">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @php
                $icons = [
                    'cafe' => '<svg class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24"><path d="M2,21H20V19H2M20,8H18V5H20M20,3H4V13A4,4 0 0,0 8,17H14A4,4 0 0,0 18,13V10H20A2,2 0 0,0 22,8V5C22,3.89 21.1,3 20,3Z"/></svg>',
                    'fastfood' => '<svg class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24"><path d="M1,21.98C1,22.54 1.46,23 2.02,23H6.98C7.54,23 8,22.54 8,21.98V20H1V21.98M3.5,11L1,13.5V18H8V13.5L5.5,11M9,9V10H16L14.5,4H13.5L12,9M21.5,3H19.5L19.92,4.43C21.16,4.86 22,6 22,7.35V10C22,11.1 21.1,12 20,12H17V21.98C17,22.54 17.46,23 18.02,23H22.98C23.54,23 24,22.54 24,21.98V8.35C24,5.5 22.03,3.13 19.5,3H21.5Z"/></svg>',
                    'hotel' => '<svg class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24"><path d="M19,7H11V14H3V5H1V20H3V17H21V20H23V11A4,4 0 0,0 19,7M7,13A3,3 0 0,0 10,10A3,3 0 0,0 7,7A3,3 0 0,0 4,10A3,3 0 0,0 7,13Z"/></svg>',
                    'event' => '<svg class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24"><path d="M19,19H5V8H19M16,1V3H8V1H6V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3H18V1M17,12H12V17H17V12Z"/></svg>',
                    'retail' => '<svg class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24"><path d="M16,18H7V11H9V13H11V11H13V13H15V11H17V18M17,6V8H7V6H17M20,4H4V10A2,2 0 0,0 6,12V20H18V12A2,2 0 0,0 20,10V4Z"/></svg>',
                ];
                $bgImages = [
                    'cafe' => 'images/lifestyle-iced-coffee.webp',
                    'fastfood' => 'images/product-kraft-bag.webp',
                    'hotel' => 'images/catalog/asset-19.jpg',
                    'event' => 'images/lifestyle-cocktail.webp',
                    'retail' => 'images/social-coffee.webp',
                ];
            @endphp

            @foreach($solutions as $solution)
                <article class="group flex h-full flex-col overflow-hidden rounded-lg border-l-4 border-transparent bg-white transition-all duration-300 hover:border-primary-yellow hover:shadow-2xl" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="relative h-56 overflow-hidden bg-slate-200">
                        <img
                            src="{{ asset($bgImages[$solution['key']] ?? 'images/lifestyle-drinks.webp') }}"
                            alt="Lunar Ambalaj Solution"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            width="896"
                            height="448"
                            loading="lazy"
                            decoding="async"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/40 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 z-10 flex items-center gap-3 text-white">
                            <div class="rounded-lg bg-white/20 p-2 backdrop-blur">
                                {!! $icons[$solution['key']] ?? $icons['cafe'] !!}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col p-6">
                        <h2 class="mb-3 font-heading text-xl font-bold uppercase text-slate-900 transition-colors group-hover:text-primary-yellow">
                            {{ data_get($solution, "title.$locale") ?? data_get($solution, 'title.en') }}
                        </h2>
                        <p class="mb-4 flex-1 text-sm leading-relaxed text-slate-600">
                            {{ data_get($solution, "body.$locale") ?? data_get($solution, 'body.en') }}
                        </p>
                        <div class="mt-4 border-t border-slate-200 pt-4">
                            <p class="mb-2 text-xs font-bold uppercase tracking-wide text-slate-500">
                                {{ $ui['set_label'][$locale] ?? $ui['set_label']['en'] }}
                            </p>
                            <p class="text-sm font-medium text-slate-800">
                                {{ data_get($solution, "set.$locale") ?? data_get($solution, 'set.en') }}
                            </p>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route(app()->getLocale() . '.quote') }}" class="inline-flex items-center gap-2 bg-primary-yellow px-4 py-2.5 text-sm font-bold uppercase tracking-wide text-dark-charcoal shadow-md transition-colors hover:bg-primary-yellow/90 group-hover:shadow-lg">
                                {{ $ui['cta_fast_quote'][$locale] ?? $ui['cta_fast_quote']['en'] }}
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-4">
        <div class="mb-12 text-center">
            <div class="mb-4 inline-block border-l-4 border-primary-yellow bg-slate-100 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-slate-900">
                {{ $ui['process_badge'][$locale] ?? $ui['process_badge']['en'] }}
            </div>
            <h2 class="font-heading text-3xl font-bold uppercase text-slate-900 md:text-4xl">
                {{ $ui['process_title'][$locale] ?? $ui['process_title']['en'] }}
            </h2>
        </div>

        <div class="grid gap-6 md:grid-cols-4">
            @foreach($steps as $step)
                <div class="relative border-l-4 border-primary-yellow bg-slate-50 p-6 transition-all hover:shadow-lg" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="absolute right-4 top-2 text-6xl font-black text-primary-yellow/20">{{ $step['num'] }}</div>
                    <div class="relative z-10">
                        <h3 class="mb-2 text-lg font-bold text-slate-900">{{ data_get($step, "title.$locale") ?? data_get($step, 'title.en') }}</h3>
                        <p class="text-sm text-slate-600">{{ data_get($step, "desc.$locale") ?? data_get($step, 'desc.en') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-gradient-to-br from-primary-yellow to-amber-500 py-16">
    <div class="mx-auto max-w-4xl px-4 text-center">
        <h2 class="mb-4 font-heading text-3xl font-bold uppercase text-dark-charcoal md:text-4xl">
            {{ $ui['final_title'][$locale] ?? $ui['final_title']['en'] }}
        </h2>
        <p class="mx-auto mb-8 max-w-2xl text-lg text-dark-charcoal/80">
            {{ $ui['final_desc'][$locale] ?? $ui['final_desc']['en'] }}
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route(app()->getLocale() . '.quote') }}" class="inline-flex items-center gap-2 bg-dark-charcoal px-8 py-4 text-sm font-bold uppercase tracking-wide text-white transition-colors hover:bg-dark-charcoal/90 shadow-lg">
                {{ $ui['final_quote'][$locale] ?? $ui['final_quote']['en'] }}
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
            <a href="{{ route(app()->getLocale() . '.products') }}" class="inline-flex items-center gap-2 bg-white px-8 py-4 text-sm font-bold uppercase tracking-wide text-dark-charcoal transition-colors hover:bg-slate-100 shadow-lg">
                {{ $ui['final_products'][$locale] ?? $ui['final_products']['en'] }}
            </a>
        </div>
    </div>
</section>

@endsection
