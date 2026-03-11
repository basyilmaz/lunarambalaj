@extends('layouts.app')

@section('content')
@php
    $locale = app()->getLocale();
    $ui = [
        'home' => ['tr' => 'Anasayfa', 'en' => 'Home', 'ru' => 'Главная', 'ar' => 'الرئيسية', 'es' => 'Inicio'],
        'products' => ['tr' => 'Ürünler', 'en' => 'Products', 'ru' => 'Продукция', 'ar' => 'المنتجات', 'es' => 'Productos'],
        'lead_time' => ['tr' => 'Termin', 'en' => 'Lead Time', 'ru' => 'Срок', 'ar' => 'مدة التنفيذ', 'es' => 'Plazo'],
        'cta_quote' => ['tr' => '24 Saatte Teklif Alın', 'en' => 'Get Quote Within 24 Hours', 'ru' => 'Расчет за 24 часа', 'ar' => 'احصل على عرض خلال 24 ساعة', 'es' => 'Cotización en 24 Horas'],
        'cta_info' => ['tr' => 'Detaylı Bilgi', 'en' => 'More Information', 'ru' => 'Подробная информация', 'ar' => 'مزيد من المعلومات', 'es' => 'Más Información'],
        'description' => ['tr' => 'Açıklama', 'en' => 'Description', 'ru' => 'Описание', 'ar' => 'الوصف', 'es' => 'Descripción'],
        'specifications' => ['tr' => 'Teknik Özellikler', 'en' => 'Specifications', 'ru' => 'Технические характеристики', 'ar' => 'المواصفات الفنية', 'es' => 'Especificaciones Técnicas'],
        'use_cases' => ['tr' => 'Kullanım Alanları', 'en' => 'Use Cases', 'ru' => 'Области применения', 'ar' => 'مجالات الاستخدام', 'es' => 'Áreas de Uso'],
        'min_order' => ['tr' => 'Min. Sipariş', 'en' => 'Min. Order', 'ru' => 'Мин. заказ', 'ar' => 'الحد الأدنى للطلب', 'es' => 'Pedido Mínimo'],
        'units' => ['tr' => 'adet', 'en' => 'units', 'ru' => 'шт.', 'ar' => 'قطعة', 'es' => 'unidades'],
        'usecase_1_title' => ['tr' => 'Horeca Segmenti', 'en' => 'Horeca Segment', 'ru' => 'Сегмент HoReCa', 'ar' => 'قطاع الهوريكا', 'es' => 'Segmento Horeca'],
        'usecase_1_text' => ['tr' => 'Kafe, restoran, otel ve catering operasyonları için ideal çözüm.', 'en' => 'Ideal solution for cafe, restaurant, hotel and catering operations.', 'ru' => 'Подходит для кафе, ресторанов, отелей и кейтеринга.', 'ar' => 'حل مناسب للمقاهي والمطاعم والفنادق وخدمات التموين.', 'es' => 'Solución ideal para cafeterías, restaurantes, hoteles y catering.'],
        'usecase_2_title' => ['tr' => 'Zincir Şubeler', 'en' => 'Chain Branches', 'ru' => 'Сетевые точки', 'ar' => 'الفروع والسلاسل', 'es' => 'Cadenas y Sucursales'],
        'usecase_2_text' => ['tr' => 'Fast-food ve zincir şube standartlaştırma süreçleri için tutarlı kalite.', 'en' => 'Consistent quality for fast-food and chain branch standardization workflows.', 'ru' => 'Стабильное качество для стандартизации сетевых и fast-food точек.', 'ar' => 'جودة ثابتة لعمليات التوحيد في مطاعم الوجبات السريعة والسلاسل.', 'es' => 'Calidad consistente para estandarización en fast-food y sucursales de cadena.'],
        'usecase_3_title' => ['tr' => 'Etkinlik ve Organizasyonlar', 'en' => 'Events and Organizations', 'ru' => 'Мероприятия и организации', 'ar' => 'الفعاليات والتنظيمات', 'es' => 'Eventos y Organizaciones'],
        'usecase_3_text' => ['tr' => 'Özel baskılı ürünlerle etkinlik, lansman ve promosyon sunumları.', 'en' => 'Custom printed products for event, launch and promotion serving scenarios.', 'ru' => 'Сценарии мероприятий, запусков и промо с индивидуальной печатью.', 'ar' => 'منتجات مطبوعة مخصصة للفعاليات والإطلاقات والعروض الترويجية.', 'es' => 'Productos impresos a medida para eventos, lanzamientos y acciones promocionales.'],
        'related_subtitle' => ['tr' => 'Benzer Ürünler', 'en' => 'Related Products', 'ru' => 'Похожие продукты', 'ar' => 'منتجات مشابهة', 'es' => 'Productos Relacionados'],
        'related_title' => ['tr' => 'Aynı Kategoriden Diğer Ürünler', 'en' => 'Other Products from Same Category', 'ru' => 'Другие продукты из этой категории', 'ar' => 'منتجات أخرى من نفس الفئة', 'es' => 'Otros Productos de la Misma Categoría'],
    ];

    $purchaseFlow = [
        'title' => ['tr' => 'Satın Alma Akışı', 'en' => 'Procurement Flow', 'ru' => 'Процесс закупки', 'ar' => 'مسار الشراء', 'es' => 'Flujo de Compra'],
        'steps' => [
            [
                'label' => ['tr' => 'Teklif', 'en' => 'Quote', 'ru' => 'Расчет', 'ar' => 'عرض السعر', 'es' => 'Cotizacion'],
                'value' => ['tr' => '24 Saat', 'en' => '24 Hours', 'ru' => '24 часа', 'ar' => '24 ساعة', 'es' => '24 Horas'],
            ],
            [
                'label' => ['tr' => 'Termin', 'en' => 'Lead Time', 'ru' => 'Срок', 'ar' => 'المدة', 'es' => 'Plazo'],
                'value' => ['tr' => $leadTimeDisplay, 'en' => $leadTimeDisplay, 'ru' => $leadTimeDisplay, 'ar' => $leadTimeDisplay, 'es' => $leadTimeDisplay],
            ],
            [
                'label' => ['tr' => 'Paketleme', 'en' => 'Packaging', 'ru' => 'Упаковка', 'ar' => 'التعبئة', 'es' => 'Empaque'],
                'value' => ['tr' => 'Jelatinli / Jelatinsiz', 'en' => 'Wrapped / Unwrapped', 'ru' => 'С оберткой / Без', 'ar' => 'مغلف / غير مغلف', 'es' => 'Con envoltura / Sin envoltura'],
            ],
        ],
    ];
@endphp

<!-- Product Hero -->
<section class="bg-white py-12 border-b border-slate-200">
    <div class="mx-auto max-w-7xl px-4">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-slate-600 mb-8">
            <a href="{{ route(app()->getLocale() . '.home') }}" class="hover:text-primary-yellow">
                {{ $ui['home'][$locale] ?? $ui['home']['en'] }}
            </a>
            <span>/</span>
            <a href="{{ route(app()->getLocale() . '.products') }}" class="hover:text-primary-yellow">
                {{ $ui['products'][$locale] ?? $ui['products']['en'] }}
            </a>
            <span>/</span>
            @if($product->category)
                @php $catTranslation = $product->category->translation(app()->getLocale()); @endphp
                @if($catTranslation)
                    <a href="{{ route(app()->getLocale() . '.products', ['category' => $catTranslation->slug]) }}" class="hover:text-primary-yellow">
                        {{ $catTranslation->name }}
                    </a>
                    <span>/</span>
                @endif
            @endif
            <span class="text-slate-900 font-medium">{{ $translation->name }}</span>
        </nav>

        <div class="grid md:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div class="relative">
                <div class="aspect-square bg-slate-100 rounded-xl overflow-hidden">
                    <img src="{{ asset(\App\Support\AssetVariant::optimized($product->image, 'images/category-straws.svg')) }}"
                         alt="{{ $translation->name }}"
                         class="w-full h-full object-cover"
                         loading="eager"
                         fetchpriority="high"
                         width="960"
                         height="960"
                         decoding="async">
                </div>
                @if($product->category)
                    @php $catT = $product->category->translation(app()->getLocale()); @endphp
                    @if($catT)
                        <div class="absolute top-4 left-4 bg-primary-yellow text-dark-charcoal px-4 py-2 text-xs font-bold uppercase tracking-wider shadow-lg">
                            {{ $catT->name }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-slate-900 font-heading uppercase mb-4 leading-tight">
                    {{ $translation->name }}
                </h1>
                <p class="text-lg text-slate-600 leading-relaxed mb-6">
                    {{ $translation->short_desc }}
                </p>

                <!-- Key Features -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-slate-50 p-4 border-l-4 border-primary-yellow">
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">{{ $ui['min_order'][$locale] ?? $ui['min_order']['en'] }}</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($product->min_order) }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 border-l-4 border-{{ $product->has_print ? 'success-green' : 'slate-300' }}">
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">{{ __('site.product.print_label') }}</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $product->has_print ? __('site.common.yes') : __('site.common.no') }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 border-l-4 border-{{ $product->has_wrapping ? 'success-green' : 'slate-300' }}">
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">{{ __('site.product.wrapping_label') }}</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $product->has_wrapping ? __('site.common.yes') : __('site.common.no') }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 border-l-4 border-info-blue">
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">{{ $ui['lead_time'][$locale] ?? $ui['lead_time']['en'] }}</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $leadTimeDisplay }}</p>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-4">
                    <x-button variant="primary" :href="route(app()->getLocale() . '.quote')" size="lg">
                        {{ $ui['cta_quote'][$locale] ?? $ui['cta_quote']['en'] }}
                        <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </x-button>
                    <x-button variant="outline" :href="route(app()->getLocale() . '.contact')" size="lg">
                        {{ $ui['cta_info'][$locale] ?? $ui['cta_info']['en'] }}
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-dark-charcoal py-10">
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <h2 class="text-2xl font-bold uppercase text-white">
                {{ $purchaseFlow['title'][$locale] ?? $purchaseFlow['title']['en'] }}
            </h2>
            <a href="{{ route(app()->getLocale() . '.quote') }}" class="inline-flex items-center gap-2 bg-primary-yellow px-5 py-3 text-xs font-bold uppercase tracking-wide text-dark-charcoal">
                {{ $ui['cta_quote'][$locale] ?? $ui['cta_quote']['en'] }}
            </a>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @foreach($purchaseFlow['steps'] as $step)
                <article class="border border-slate-700 bg-slate-800/60 p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-300">{{ $step['label'][$locale] ?? $step['label']['en'] }}</p>
                    <p class="mt-2 text-xl font-bold text-primary-yellow">{{ $step['value'][$locale] ?? $step['value']['en'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<!-- Product Details Tabs -->
<section class="py-16 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4">
        <!-- Tab Navigation -->
        <div class="border-b border-slate-200 mb-8">
            <nav class="flex gap-8">
                <button class="tab-button active pb-4 px-2 text-sm font-bold uppercase tracking-wide border-b-2 border-primary-yellow text-slate-900" data-tab="description">
                    {{ $ui['description'][$locale] ?? $ui['description']['en'] }}
                </button>
                <button class="tab-button pb-4 px-2 text-sm font-bold uppercase tracking-wide border-b-2 border-transparent text-slate-500 hover:text-slate-900" data-tab="specifications">
                    {{ $ui['specifications'][$locale] ?? $ui['specifications']['en'] }}
                </button>
                <button class="tab-button pb-4 px-2 text-sm font-bold uppercase tracking-wide border-b-2 border-transparent text-slate-500 hover:text-slate-900" data-tab="usecases">
                    {{ $ui['use_cases'][$locale] ?? $ui['use_cases']['en'] }}
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="tab-content active" id="description">
            <div class="bg-white p-8 rounded-lg shadow-sm max-w-4xl">
                <article class="prose prose-slate max-w-none text-slate-700 leading-relaxed">
                    {!! nl2br(e((string) $translation->description)) !!}
                </article>
            </div>
        </div>

        <div class="tab-content hidden" id="specifications">
            <div class="bg-white p-8 rounded-lg shadow-sm max-w-4xl">
                <h3 class="text-2xl font-bold text-slate-900 font-heading uppercase mb-6">
                    {{ __('site.product.specs_title') }}
                </h3>
                <table class="w-full">
                    <tbody class="divide-y divide-slate-200">
                        @if(!empty($specRows))
                            @foreach($specRows as $key => $value)
                                <tr>
                                    <td class="py-4 pr-8 text-sm font-bold text-slate-900 uppercase tracking-wide">{{ $key }}</td>
                                    <td class="py-4 text-sm text-slate-700">{{ $value }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="py-4 text-sm text-slate-500" colspan="2">{{ __('site.product.no_specs') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="py-4 pr-8 text-sm font-bold text-slate-900 uppercase tracking-wide">{{ $ui['min_order'][$locale] ?? $ui['min_order']['en'] }}</td>
                            <td class="py-4 text-sm text-slate-700">{{ number_format($product->min_order) }} {{ $ui['units'][$locale] ?? $ui['units']['en'] }}</td>
                        </tr>
                        <tr>
                            <td class="py-4 pr-8 text-sm font-bold text-slate-900 uppercase tracking-wide">{{ __('site.product.print_label') }}</td>
                            <td class="py-4 text-sm text-slate-700">{{ $product->has_print ? __('site.common.yes') : __('site.common.no') }}</td>
                        </tr>
                        <tr>
                            <td class="py-4 pr-8 text-sm font-bold text-slate-900 uppercase tracking-wide">{{ __('site.product.wrapping_label') }}</td>
                            <td class="py-4 text-sm text-slate-700">{{ $product->has_wrapping ? __('site.common.yes') : __('site.common.no') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-content hidden" id="usecases">
            <div class="bg-white p-8 rounded-lg shadow-sm max-w-4xl">
                <h3 class="text-2xl font-bold text-slate-900 font-heading uppercase mb-6">
                    {{ $ui['use_cases'][$locale] ?? $ui['use_cases']['en'] }}
                </h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-yellow/10 rounded-full flex items-center justify-center mt-1">
                            <svg class="w-4 h-4 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 mb-1">{{ $ui['usecase_1_title'][$locale] ?? $ui['usecase_1_title']['en'] }}</p>
                            <p class="text-slate-600">{{ $ui['usecase_1_text'][$locale] ?? $ui['usecase_1_text']['en'] }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-yellow/10 rounded-full flex items-center justify-center mt-1">
                            <svg class="w-4 h-4 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 mb-1">{{ $ui['usecase_2_title'][$locale] ?? $ui['usecase_2_title']['en'] }}</p>
                            <p class="text-slate-600">{{ $ui['usecase_2_text'][$locale] ?? $ui['usecase_2_text']['en'] }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-yellow/10 rounded-full flex items-center justify-center mt-1">
                            <svg class="w-4 h-4 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 mb-1">{{ $ui['usecase_3_title'][$locale] ?? $ui['usecase_3_title']['en'] }}</p>
                            <p class="text-slate-600">{{ $ui['usecase_3_text'][$locale] ?? $ui['usecase_3_text']['en'] }}</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if(isset($relatedProducts) && $relatedProducts->count() > 0)
<section class="py-16 bg-white">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="$ui['related_subtitle'][$locale] ?? $ui['related_subtitle']['en']"
            :title="$ui['related_title'][$locale] ?? $ui['related_title']['en']"
            align="left"
        />

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 mt-12">
            @foreach($relatedProducts as $relatedProduct)
                <x-card.product :product="$relatedProduct" :locale="app()->getLocale()" />
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Sticky Mobile CTA -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 shadow-lg z-40 md:hidden">
    <x-button variant="primary" :href="route(app()->getLocale() . '.quote')" class="w-full justify-center">
        {{ $ui['cta_quote'][$locale] ?? $ui['cta_quote']['en'] }}
    </x-button>
</div>

<!-- Tab Switching Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');

            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-primary-yellow', 'text-slate-900');
                btn.classList.add('border-transparent', 'text-slate-500');
            });
            tabContents.forEach(content => content.classList.add('hidden'));

            // Add active class to clicked button and show target content
            button.classList.add('active', 'border-primary-yellow', 'text-slate-900');
            button.classList.remove('border-transparent', 'text-slate-500');
            document.getElementById(targetTab).classList.remove('hidden');
        });
    });
});
</script>

<!-- GTM Event -->
<script>
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({
    event: 'view_item',
    item_name: @json($translation->name),
    item_id: '{{ $product->id }}',
    locale: '{{ app()->getLocale() }}'
});
if (typeof fbq === 'function') {
    fbq('track', 'ViewContent', {
        content_name: @json($translation->name),
        content_ids: ['{{ $product->id }}'],
        content_type: 'product'
    });
}
</script>

@endsection
