@extends('layouts.app')

@section('content')
@php
    $locale = app()->getLocale();
    $copy = [
        'tr' => [
            'subtitle' => 'Blog ve Haberler',
            'title' => 'Ambalaj Trendleri ve B2B Çözüm Rehberleri',
            'desc' => 'Ambalaj trendleri, baskı planlaması, kategori seçimi ve toplu sipariş operasyonlarını destekleyen B2B içerikler.',
            'card1' => 'Markalaşma Odaklı Ürün Rehberleri',
            'card2' => 'MOQ, Termin ve Tedarik Planlama',
            'card3' => 'Sektör Bazlı Çözüm Senaryoları',
            'empty' => 'Henüz blog yazısı bulunmuyor.',
            'ctaTitle' => 'İçerikten Uygulamaya Geçin',
            'ctaDesc' => 'Okuduğunuz konuya uygun ürün kategorilerini tek teklif dosyasında birleştirmek için hızlı teklif oluşturun.',
            'ctaQuote' => '24 Saatte Teklif Alın',
            'ctaProducts' => 'Ürünleri İncele',
        ],
        'en' => [
            'subtitle' => 'Blog & News',
            'title' => 'Packaging Trends and B2B Solution Guides',
            'desc' => 'B2B content on packaging trends, print planning, category selection and bulk-order operations.',
            'card1' => 'Brand-Focused Product Guides',
            'card2' => 'MOQ, Lead-Time and Supply Planning',
            'card3' => 'Segment-Based Solution Scenarios',
            'empty' => 'No blog posts available yet.',
            'ctaTitle' => 'Move from Content to Execution',
            'ctaDesc' => 'Create a fast quote to combine relevant product categories in a single quotation workflow.',
            'ctaQuote' => 'Get Quote Within 24 Hours',
            'ctaProducts' => 'Browse Products',
        ],
        'ru' => [
            'subtitle' => 'Блог и новости',
            'title' => 'Тренды упаковки и B2B-гайды',
            'desc' => 'Материалы о трендах упаковки, планировании печати, выборе категорий и управлении оптовыми заказами.',
            'card1' => 'Гайды по продуктам для бренда',
            'card2' => 'MOQ, сроки и планирование поставок',
            'card3' => 'Сценарии решений по сегментам',
            'empty' => 'Пока нет опубликованных статей.',
            'ctaTitle' => 'От контента к действию',
            'ctaDesc' => 'Сформируйте быстрый запрос, чтобы объединить нужные категории в одном коммерческом предложении.',
            'ctaQuote' => 'Получить расчет за 24 часа',
            'ctaProducts' => 'Смотреть продукты',
        ],
        'ar' => [
            'subtitle' => 'المدونة والأخبار',
            'title' => 'اتجاهات التعبئة ودلائل حلول B2B',
            'desc' => 'محتوى B2B حول اتجاهات التعبئة وتخطيط الطباعة واختيار الفئات وإدارة الطلبات الكبيرة.',
            'card1' => 'أدلة منتجات تركز على العلامة',
            'card2' => 'MOQ والمدة وتخطيط التوريد',
            'card3' => 'سيناريوهات حلول حسب القطاع',
            'empty' => 'لا توجد مقالات منشورة حتى الآن.',
            'ctaTitle' => 'من المحتوى إلى التنفيذ',
            'ctaDesc' => 'أنشئ طلب عرض سريع لدمج الفئات المناسبة في ملف عرض واحد.',
            'ctaQuote' => 'احصل على عرض خلال 24 ساعة',
            'ctaProducts' => 'استعرض المنتجات',
        ],
        'es' => [
            'subtitle' => 'Blog y Novedades',
            'title' => 'Tendencias de Empaque y Guías de Solución B2B',
            'desc' => 'Contenido B2B sobre tendencias de empaque, planificación de impresión, selección de categorías y operaciones de pedidos masivos.',
            'card1' => 'Guías de producto orientadas a marca',
            'card2' => 'MOQ, plazos y planificación de suministro',
            'card3' => 'Escenarios de solución por segmento',
            'empty' => 'Aún no hay artículos publicados.',
            'ctaTitle' => 'De Contenido a Ejecución',
            'ctaDesc' => 'Genera una cotización rápida para combinar categorías de producto relevantes en un solo flujo comercial.',
            'ctaQuote' => 'Recibir Cotización en 24 Horas',
            'ctaProducts' => 'Ver Productos',
        ],
    ];
    $t = $copy[$locale] ?? $copy['en'];
@endphp

<x-hero
    :subtitle="$t['subtitle']"
    :title="$t['title']"
    height="min-h-[400px]"
>
    <p class="mb-10 max-w-2xl text-xl font-light leading-relaxed text-slate-300">
        {{ $t['desc'] }}
    </p>
</x-hero>

<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-6 md:grid-cols-3">
            <div class="border-l-4 border-primary-yellow bg-white p-6" data-aos="fade-up">
                <svg class="mb-3 h-10 w-10 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-bold uppercase tracking-wide text-slate-900">{{ $t['card1'] }}</p>
            </div>
            <div class="border-l-4 border-info-blue bg-white p-6" data-aos="fade-up" data-aos-delay="100">
                <svg class="mb-3 h-10 w-10 text-info-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-bold uppercase tracking-wide text-slate-900">{{ $t['card2'] }}</p>
            </div>
            <div class="border-l-4 border-success-green bg-white p-6" data-aos="fade-up" data-aos-delay="200">
                <svg class="mb-3 h-10 w-10 text-success-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="text-sm font-bold uppercase tracking-wide text-slate-900">{{ $t['card3'] }}</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-4">
        @if($posts->count() > 0)
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    @php $postTranslation = $post->translation(app()->getLocale()); @endphp
                    @if($postTranslation)
                        <x-card.blog :post="$post" :locale="app()->getLocale()" />
                    @endif
                @endforeach
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <svg class="mx-auto mb-4 h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-lg text-slate-600">{{ $t['empty'] }}</p>
            </div>
        @endif
    </div>
</section>

<section class="bg-dark-charcoal py-16">
    <div class="mx-auto max-w-7xl px-4 text-center">
        <h2 class="mb-4 font-heading text-3xl font-bold uppercase text-white md:text-4xl" data-aos="fade-up">
            {{ $t['ctaTitle'] }}
        </h2>
        <p class="mx-auto mb-8 max-w-2xl text-lg text-slate-300" data-aos="fade-up" data-aos-delay="100">
            {{ $t['ctaDesc'] }}
        </p>
        <div class="flex flex-wrap justify-center gap-4" data-aos="fade-up" data-aos-delay="200">
            <x-button variant="primary" :href="route(app()->getLocale() . '.quote')" size="lg">
                {{ $t['ctaQuote'] }}
            </x-button>
            <x-button variant="outline" :href="route(app()->getLocale() . '.products')" size="lg" class="!border-slate-500 !text-white hover:!border-white hover:!bg-white/10">
                {{ $t['ctaProducts'] }}
            </x-button>
        </div>
    </div>
</section>

@endsection
