@props(['product', 'locale' => 'tr'])

@php
    $translation = $product->translation($locale);
    $optimizedProductImage = \App\Support\AssetVariant::optimized($product->image, 'images/category-straws.svg');
@endphp

@if($translation)
<a href="{{ route($locale . '.products.show', $translation->slug) }}"
   class="group relative block h-full overflow-hidden bg-white shadow-sm transition-all duration-300 hover:shadow-xl"
   data-aos="fade-up">
    <div class="relative h-64 overflow-hidden bg-slate-100">
        <img
            src="{{ asset($optimizedProductImage) }}"
            alt="{{ $translation->name }}"
            loading="lazy"
            width="960"
            height="640"
            decoding="async"
            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-dark-charcoal/80 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>

        @if($product->category)
            @php $catTrans = $product->category->translation($locale); @endphp
            @if($catTrans)
                <div class="absolute left-4 top-4 bg-primary-yellow px-3 py-1 text-xs font-bold uppercase text-dark-charcoal">
                    {{ $catTrans->name }}
                </div>
            @endif
        @endif

        @if($product->min_order)
            <div class="absolute right-4 top-4 bg-white px-3 py-1 text-xs font-semibold text-slate-900">
                MOQ: {{ number_format($product->min_order) }}
            </div>
        @endif
    </div>

    <div class="p-6">
        <h3 class="mb-2 font-heading text-xl font-bold uppercase text-slate-900 transition-colors group-hover:text-primary-yellow">
            {{ $translation->name }}
        </h3>

        @if($translation->short_desc)
            <p class="mb-4 line-clamp-2 text-sm leading-relaxed text-text-gray">
                {{ $translation->short_desc }}
            </p>
        @endif

        <div class="mb-4 flex flex-wrap gap-2">
            @if($product->has_print)
                <span class="rounded bg-slate-100 px-2 py-1 text-xs text-text-gray">
                    <svg class="mr-1 inline h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                    @php
                        $printText = [
                            'tr' => 'Baskı Mevcut',
                            'en' => 'Print Available',
                            'ru' => 'Печать доступна',
                            'ar' => 'الطباعة متاحة',
                            'es' => 'Impresión Disponible',
                        ];
                    @endphp
                    {{ $printText[$locale] ?? $printText['en'] }}
                </span>
            @endif

            @if($product->has_wrapping)
                <span class="rounded bg-slate-100 px-2 py-1 text-xs text-text-gray">
                    <svg class="mr-1 inline h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
                    @php
                        $wrappingText = [
                            'tr' => 'Paketleme',
                            'en' => 'Wrapping',
                            'ru' => 'Упаковка',
                            'ar' => 'التغليف',
                            'es' => 'Empaque',
                        ];
                    @endphp
                    {{ $wrappingText[$locale] ?? $wrappingText['en'] }}
                </span>
            @endif
        </div>

        <div class="flex items-center gap-2 text-sm font-bold uppercase text-primary-yellow transition-all group-hover:gap-4">
            @php
                $viewDetailsText = [
                    'tr' => 'Detayları Gör',
                    'en' => 'View Details',
                    'ru' => 'Подробнее',
                    'ar' => 'عرض التفاصيل',
                    'es' => 'Ver Detalles',
                ];
            @endphp
            {{ $viewDetailsText[$locale] ?? $viewDetailsText['en'] }}
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </div>
    </div>
</a>
@endif
