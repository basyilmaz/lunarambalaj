@extends('layouts.app')

@section('content')

<!-- Product Hero -->
<section class="bg-white py-12 border-b border-slate-200">
    <div class="mx-auto max-w-7xl px-4">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-slate-600 mb-8">
            <a href="{{ route(app()->getLocale() . '.home') }}" class="hover:text-primary-yellow">
                {{ app()->getLocale() === 'tr' ? 'Anasayfa' : 'Home' }}
            </a>
            <span>/</span>
            <a href="{{ route(app()->getLocale() . '.products') }}" class="hover:text-primary-yellow">
                {{ app()->getLocale() === 'tr' ? 'Ürünler' : 'Products' }}
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
                    <img src="{{ asset($product->image ?: 'images/category-straws.svg') }}"
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
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">{{ __('site.product.min_order') }}</p>
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
                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">{{ app()->getLocale() === 'tr' ? 'Termin' : 'Lead Time' }}</p>
                        <p class="text-2xl font-bold text-slate-900">{{ app()->getLocale() === 'tr' ? '15 gün' : '15 days' }}</p>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-4">
                    <x-button variant="primary" :href="route(app()->getLocale() . '.quote')" size="lg">
                        {{ app()->getLocale() === 'tr' ? '24 Saatte Teklif Alın' : 'Get Quote Within 24 Hours' }}
                        <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </x-button>
                    <x-button variant="outline" :href="route(app()->getLocale() . '.contact')" size="lg">
                        {{ app()->getLocale() === 'tr' ? 'Detaylı Bilgi' : 'More Information' }}
                    </x-button>
                </div>
            </div>
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
                    {{ app()->getLocale() === 'tr' ? 'Açıklama' : 'Description' }}
                </button>
                <button class="tab-button pb-4 px-2 text-sm font-bold uppercase tracking-wide border-b-2 border-transparent text-slate-500 hover:text-slate-900" data-tab="specifications">
                    {{ app()->getLocale() === 'tr' ? 'Teknik Özellikler' : 'Specifications' }}
                </button>
                <button class="tab-button pb-4 px-2 text-sm font-bold uppercase tracking-wide border-b-2 border-transparent text-slate-500 hover:text-slate-900" data-tab="usecases">
                    {{ app()->getLocale() === 'tr' ? 'Kullanım Alanları' : 'Use Cases' }}
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
                        @if($product->specs)
                            @foreach($product->specs as $key => $value)
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
                            <td class="py-4 pr-8 text-sm font-bold text-slate-900 uppercase tracking-wide">{{ app()->getLocale() === 'tr' ? 'Min. Sipariş' : 'Min. Order' }}</td>
                            <td class="py-4 text-sm text-slate-700">{{ number_format($product->min_order) }} {{ app()->getLocale() === 'tr' ? 'adet' : 'units' }}</td>
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
                    {{ app()->getLocale() === 'tr' ? 'Kullanım Alanları' : 'Use Cases' }}
                </h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-yellow/10 rounded-full flex items-center justify-center mt-1">
                            <svg class="w-4 h-4 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 mb-1">{{ app()->getLocale() === 'tr' ? 'Horeca Segmenti' : 'Horeca Segment' }}</p>
                            <p class="text-slate-600">{{ app()->getLocale() === 'tr' ? 'Kafe, restoran, otel ve catering operasyonları için ideal çözüm.' : 'Ideal solution for cafe, restaurant, hotel and catering operations.' }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-yellow/10 rounded-full flex items-center justify-center mt-1">
                            <svg class="w-4 h-4 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 mb-1">{{ app()->getLocale() === 'tr' ? 'Zincir Şubeler' : 'Chain Branches' }}</p>
                            <p class="text-slate-600">{{ app()->getLocale() === 'tr' ? 'Fast-food ve zincir şube standartlaştırma süreçleri için tutarlı kalite.' : 'Consistent quality for fast-food and chain branch standardization workflows.' }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-yellow/10 rounded-full flex items-center justify-center mt-1">
                            <svg class="w-4 h-4 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 mb-1">{{ app()->getLocale() === 'tr' ? 'Etkinlik ve Organizasyonlar' : 'Events and Organizations' }}</p>
                            <p class="text-slate-600">{{ app()->getLocale() === 'tr' ? 'Özel baskılı ürünlerle etkinlik, lansman ve promosyon sunumları.' : 'Custom printed products for event, launch and promotion serving scenarios.' }}</p>
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
            :subtitle="app()->getLocale() === 'tr' ? 'Benzer Ürünler' : 'Related Products'"
            :title="app()->getLocale() === 'tr' ? 'Aynı Kategoriden Diğer Ürünler' : 'Other Products from Same Category'"
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
        {{ app()->getLocale() === 'tr' ? '24 Saatte Teklif Alın' : 'Get Quote Within 24 Hours' }}
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
