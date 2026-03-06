@extends('layouts.app')

@section('content')
@php
    $locale = app()->getLocale();
    $ui = [
        'hero_badge' => ['tr' => 'Sektöre Özel', 'en' => 'Industry Solutions', 'ru' => 'Отраслевой фокус', 'ar' => 'حلول قطاعية'],
        'hero_title' => ['tr' => 'Sektöre Göre Çözümler', 'en' => 'Solutions by Segment', 'ru' => 'Решения по сегментам', 'ar' => 'حلول حسب القطاع'],
        'hero_desc' => [
            'tr' => 'Kafe, otel, fast-food, catering ve etkinlik operasyonları için ürün kombinasyonlarını tek tedarik modeliyle planlıyoruz.',
            'en' => 'We plan product bundles for cafe, hotel, fast-food, catering and event operations with a single-supplier model.',
            'ru' => 'Планируем продуктовые наборы для кафе, отелей, fast-food, кейтеринга и мероприятий по модели единого поставщика.',
            'ar' => 'نخطط مجموعات المنتجات للمقاهي والفنادق والوجبات السريعة والتموين والفعاليات بنموذج المورد الواحد.',
        ],
        'set_label' => ['tr' => 'Önerilen Set:', 'en' => 'Suggested Set:', 'ru' => 'Рекомендуемый набор:', 'ar' => 'الطقم المقترح:'],
        'cta_fast_quote' => ['tr' => 'Hızlı Teklif Al', 'en' => 'Get Fast Quote', 'ru' => 'Быстрый расчет', 'ar' => 'احصل على عرض سريع'],
        'process_badge' => ['tr' => 'Süreç', 'en' => 'Process', 'ru' => 'Процесс', 'ar' => 'العملية'],
        'process_title' => ['tr' => 'Nasıl Çalışıyoruz?', 'en' => 'How We Work', 'ru' => 'Как мы работаем?', 'ar' => 'كيف نعمل؟'],
        'final_title' => ['tr' => 'Özel Çözüm Paketi Hazırlayalım', 'en' => 'Let\'s Prepare a Custom Solution Package', 'ru' => 'Подготовим индивидуальный пакет решений', 'ar' => 'لنجهّز باقة حلول مخصصة'],
        'final_desc' => [
            'tr' => 'Proje bazlı taleplerde numune, termin ve sevkiyat planlaması ile ilerliyoruz. Kategori setinizi belirtin, 24 saat içinde detaylı teklif alalım.',
            'en' => 'Project-based requests are handled with sampling, lead-time and shipment planning. Specify your category set and get a detailed quote within 24 hours.',
            'ru' => 'Для проектных запросов работаем с образцами, сроками и планом отгрузки. Укажите категории и получите расчет за 24 часа.',
            'ar' => 'في الطلبات القائمة على المشاريع نعمل عبر العينات والتوقيت وخطة الشحن. حدّدوا الفئات واحصلوا على عرض خلال 24 ساعة.',
        ],
        'final_quote' => ['tr' => '24 Saatte Teklif Alın', 'en' => 'Get Quote in 24h', 'ru' => 'Расчет за 24 часа', 'ar' => 'عرض سعر خلال 24 ساعة'],
        'final_products' => ['tr' => 'Ürünleri İncele', 'en' => 'Browse Products', 'ru' => 'Смотреть продукты', 'ar' => 'استعرض المنتجات'],
    ];
@endphp

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-yellow via-amber-400 to-yellow-500 min-h-[450px] flex items-center overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23000" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <!-- Content -->
    <div class="relative max-w-7xl mx-auto px-4 py-20 w-full">
        <div class="inline-block mb-4 px-4 py-1.5 border-l-4 border-dark-charcoal bg-dark-charcoal/10 backdrop-blur text-dark-charcoal text-xs font-bold uppercase tracking-[0.2em]">
            {{ $ui['hero_badge'][$locale] ?? $ui['hero_badge']['en'] }}
        </div>

        <h1 class="text-4xl md:text-5xl font-bold text-dark-charcoal font-heading leading-tight mb-6">
            {{ $ui['hero_title'][$locale] ?? $ui['hero_title']['en'] }}
        </h1>

        <p class="text-xl text-dark-charcoal/80 max-w-3xl font-light leading-relaxed">
            {{ $ui['hero_desc'][$locale] ?? $ui['hero_desc']['en'] }}
        </p>
    </div>
</section>

<!-- Solutions Grid -->
<section class="py-16 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @php
                $icons = [
                    'cafe' => '<svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M2,21H20V19H2M20,8H18V5H20M20,3H4V13A4,4 0 0,0 8,17H14A4,4 0 0,0 18,13V10H20A2,2 0 0,0 22,8V5C22,3.89 21.1,3 20,3Z"/></svg>',
                    'fastfood' => '<svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M1,21.98C1,22.54 1.46,23 2.02,23H6.98C7.54,23 8,22.54 8,21.98V20H1V21.98M3.5,11L1,13.5V18H8V13.5L5.5,11M9,9V10H16L14.5,4H13.5L12,9M21.5,3H19.5L19.92,4.43C21.16,4.86 22,6 22,7.35V10C22,11.1 21.1,12 20,12H17V21.98C17,22.54 17.46,23 18.02,23H22.98C23.54,23 24,22.54 24,21.98V8.35C24,5.5 22.03,3.13 19.5,3H21.5Z"/></svg>',
                    'hotel' => '<svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M19,7H11V14H3V5H1V20H3V17H21V20H23V11A4,4 0 0,0 19,7M7,13A3,3 0 0,0 10,10A3,3 0 0,0 7,7A3,3 0 0,0 4,10A3,3 0 0,0 7,13Z"/></svg>',
                    'event' => '<svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M19,19H5V8H19M16,1V3H8V1H6V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3H18V1M17,12H12V17H17V12Z"/></svg>',
                    'retail' => '<svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M16,18H7V11H9V13H11V11H13V13H15V11H17V18M17,6V8H7V6H17M20,4H4V10A2,2 0 0,0 6,12V20H18V12A2,2 0 0,0 20,10V4Z"/></svg>',
                ];
                $colors = [
                    'cafe' => 'from-amber-500 to-orange-600',
                    'fastfood' => 'from-red-500 to-pink-600',
                    'hotel' => 'from-blue-500 to-indigo-600',
                    'event' => 'from-purple-500 to-pink-600',
                    'retail' => 'from-green-500 to-teal-600',
                ];
            @endphp

            @foreach($solutions as $solution)
                <article class="group bg-white rounded-lg overflow-hidden flex flex-col h-full hover:shadow-2xl transition-all duration-300 border-l-4 border-transparent hover:border-primary-yellow" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <!-- Image Header -->
                    <div class="h-56 relative overflow-hidden bg-slate-200">
                        @php
                            $bgImages = [
                                'cafe' => 'images/lifestyle-iced-coffee.webp',
                                'fastfood' => 'images/product-kraft-bag.webp',
                                'hotel' => 'images/catalog/asset-19.jpg',
                                'event' => 'images/lifestyle-cocktail.webp',
                                'retail' => 'images/social-coffee.webp',
                            ];
                            $imageSrc = asset($bgImages[$solution['key']] ?? 'images/lifestyle-drinks.webp');
                        @endphp
                        <img
                            src="{{ $imageSrc }}"
                            alt="Lunar Ambalaj Solution"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            width="896"
                            height="448"
                            loading="lazy"
                            decoding="async"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/40 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white z-10 flex items-center gap-3">
                            <div class="p-2 bg-white/20 backdrop-blur rounded-lg">
                                {!! $icons[$solution['key']] ?? $icons['cafe'] !!}
                            </div>
                        </div>
                    </div>

                        <!-- Content -->
                        <div class="p-6 flex flex-col flex-1">
                            <h2 class="text-xl font-bold text-slate-900 font-heading uppercase mb-3 group-hover:text-primary-yellow transition-colors">
                                {{ data_get($solution, "title.$locale") ?? data_get($solution, 'title.en') }}
                            </h2>

                            <p class="text-slate-600 leading-relaxed mb-4 text-sm flex-1">
                                {{ data_get($solution, "body.$locale") ?? data_get($solution, 'body.en') }}
                            </p>

                            <!-- Suggested Set -->
                            <div class="pt-4 border-t border-slate-200 mt-4">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                                    {{ $ui['set_label'][$locale] ?? $ui['set_label']['en'] }}
                                </p>
                                <p class="text-sm font-medium text-slate-800">
                                    {{ data_get($solution, "set.$locale") ?? data_get($solution, 'set.en') }}
                                </p>
                            </div>

                        <!-- CTA Button -->
                        <div class="mt-6">
                            <a href="{{ route(app()->getLocale() . '.quote') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-yellow text-dark-charcoal font-bold text-sm uppercase tracking-wide hover:bg-primary-yellow/90 transition-colors shadow-md group-hover:shadow-lg">
                                {{ $ui['cta_fast_quote'][$locale] ?? $ui['cta_fast_quote']['en'] }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

<!-- Process Section -->
<section class="py-16 bg-white">
    <div class="mx-auto max-w-7xl px-4">
        <div class="text-center mb-12">
            <div class="inline-block mb-4 px-4 py-1.5 border-l-4 border-primary-yellow bg-slate-100 text-slate-900 text-xs font-bold uppercase tracking-[0.2em]">
                {{ $ui['process_badge'][$locale] ?? $ui['process_badge']['en'] }}
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 font-heading uppercase">
                {{ $ui['process_title'][$locale] ?? $ui['process_title']['en'] }}
            </h2>
        </div>

        <div class="grid md:grid-cols-4 gap-6">
            @php
                $steps = [
                    [
                        'num' => '01',
                        'title' => ['tr' => 'İhtiyaç Analizi', 'en' => 'Requirement Analysis', 'ru' => 'Анализ потребности', 'ar' => 'تحليل الاحتياج'],
                        'desc' => ['tr' => 'Kategori, adet ve kullanım senaryonuzu değerlendiriyoruz.', 'en' => 'We evaluate your category, quantity and usage scenarios.', 'ru' => 'Оцениваем категорию, объем и сценарий использования.', 'ar' => 'نقيّم الفئة والكمية وسيناريو الاستخدام.'],
                    ],
                    [
                        'num' => '02',
                        'title' => ['tr' => 'Numune / Onay', 'en' => 'Sampling / Approval', 'ru' => 'Образец / Подтверждение', 'ar' => 'العينة / الاعتماد'],
                        'desc' => ['tr' => 'Baskı proofları ve ürün numuneleriyle önizleme süreci.', 'en' => 'Preview process with print proofs and product samples.', 'ru' => 'Проверка на основе print-proof и образцов продукта.', 'ar' => 'مرحلة معاينة عبر بروفات الطباعة وعينات المنتج.'],
                    ],
                    [
                        'num' => '03',
                        'title' => ['tr' => 'Üretim Planlama', 'en' => 'Production Planning', 'ru' => 'Планирование производства', 'ar' => 'تخطيط الإنتاج'],
                        'desc' => ['tr' => 'Termin ve sevkiyat takvimi netleştirilir.', 'en' => 'Lead-time and shipment calendar confirmed.', 'ru' => 'Подтверждаем срок выполнения и календарь отгрузки.', 'ar' => 'نؤكد مدة التنفيذ وجدول الشحن.'],
                    ],
                    [
                        'num' => '04',
                        'title' => ['tr' => 'Sevkiyat ve Takip', 'en' => 'Shipment & Follow-up', 'ru' => 'Отгрузка и сопровождение', 'ar' => 'الشحن والمتابعة'],
                        'desc' => ['tr' => 'Teslimat sonrası destek ve stok yönetimi.', 'en' => 'Post-delivery support and stock management.', 'ru' => 'Поддержка после поставки и управление запасами.', 'ar' => 'دعم ما بعد التسليم وإدارة المخزون.'],
                    ],
                ];
            @endphp

            @foreach($steps as $step)
                <div class="relative bg-slate-50 p-6 border-l-4 border-primary-yellow hover:shadow-lg transition-all" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="text-6xl font-black text-primary-yellow/20 absolute top-2 right-4">
                        {{ $step['num'] }}
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            {{ data_get($step, "title.$locale") ?? data_get($step, 'title.en') }}
                        </h3>
                        <p class="text-sm text-slate-600">
                            {{ data_get($step, "desc.$locale") ?? data_get($step, 'desc.en') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-br from-primary-yellow to-amber-500">
    <div class="mx-auto max-w-4xl px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-dark-charcoal font-heading uppercase mb-4">
            {{ $ui['final_title'][$locale] ?? $ui['final_title']['en'] }}
        </h2>
        <p class="text-lg text-dark-charcoal/80 mb-8 max-w-2xl mx-auto">
            {{ $ui['final_desc'][$locale] ?? $ui['final_desc']['en'] }}
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route(app()->getLocale() . '.quote') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-dark-charcoal text-white font-bold text-sm uppercase tracking-wide hover:bg-dark-charcoal/90 transition-colors shadow-lg">
                {{ $ui['final_quote'][$locale] ?? $ui['final_quote']['en'] }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
            <a href="{{ route(app()->getLocale() . '.products') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-white text-dark-charcoal font-bold text-sm uppercase tracking-wide hover:bg-slate-100 transition-colors shadow-lg">
                {{ $ui['final_products'][$locale] ?? $ui['final_products']['en'] }}
            </a>
        </div>
    </div>
</section>

@endsection
