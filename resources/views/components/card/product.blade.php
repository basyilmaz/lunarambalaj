@props(['product', 'locale' => 'tr'])

@php
$translation = $product->translation($locale);
@endphp

@if($translation)
<a href="{{ route($locale . '.products.show', $translation->slug) }}"
   class="group block relative overflow-hidden bg-white shadow-sm hover:shadow-xl transition-all duration-300 h-full"
   data-aos="fade-up">

    <!-- Product Image -->
    <div class="relative h-64 overflow-hidden bg-slate-100">
        <img
            src="{{ asset($product->image) }}"
            alt="{{ $translation->name }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-dark-charcoal/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        <!-- Category Badge -->
        @if($product->category)
            @php $catTrans = $product->category->translation($locale); @endphp
            @if($catTrans)
                <div class="absolute top-4 left-4 bg-primary-yellow text-dark-charcoal px-3 py-1 text-xs font-bold uppercase">
                    {{ $catTrans->name }}
                </div>
            @endif
        @endif

        <!-- MOQ Badge -->
        @if($product->min_order)
            <div class="absolute top-4 right-4 bg-white text-slate-900 px-3 py-1 text-xs font-semibold">
                MOQ: {{ number_format($product->min_order) }}
            </div>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-6">
        <h3 class="text-xl font-bold text-slate-900 font-heading uppercase mb-2 group-hover:text-primary-yellow transition-colors">
            {{ $translation->name }}
        </h3>

        @if($translation->short_desc)
            <p class="text-text-gray text-sm leading-relaxed line-clamp-2 mb-4">
                {{ $translation->short_desc }}
            </p>
        @endif

        <!-- Features -->
        <div class="flex flex-wrap gap-2 mb-4">
            @if($product->has_print)
                <span class="text-xs bg-slate-100 text-text-gray px-2 py-1 rounded">
                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                    @php
                        $printText = [
                            'tr' => 'Baskı Mevcut',
                            'en' => 'Print Available',
                            'ru' => 'Печать доступна',
                            'ar' => 'الطباعة متاحة',
                        ];
                    @endphp
                    {{ $printText[$locale] ?? $printText['en'] }}
                </span>
            @endif

            @if($product->has_wrapping)
                <span class="text-xs bg-slate-100 text-text-gray px-2 py-1 rounded">
                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
                    @php
                        $wrappingText = [
                            'tr' => 'Paketleme',
                            'en' => 'Wrapping',
                            'ru' => 'Упаковка',
                            'ar' => 'التغليف',
                        ];
                    @endphp
                    {{ $wrappingText[$locale] ?? $wrappingText['en'] }}
                </span>
            @endif
        </div>

        <!-- CTA -->
        <div class="flex items-center gap-2 text-primary-yellow font-bold text-sm uppercase group-hover:gap-4 transition-all">
            @php
                $viewDetailsText = [
                    'tr' => 'Detayları Gör',
                    'en' => 'View Details',
                    'ru' => 'Подробнее',
                    'ar' => 'عرض التفاصيل',
                ];
            @endphp
            {{ $viewDetailsText[$locale] ?? $viewDetailsText['en'] }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </div>
    </div>
</a>
@endif
