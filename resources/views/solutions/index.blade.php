@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-yellow via-amber-400 to-yellow-500 min-h-[450px] flex items-center overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23000" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <!-- Content -->
    <div class="relative max-w-7xl mx-auto px-4 py-20 w-full">
        <div class="inline-block mb-4 px-4 py-1.5 border-l-4 border-dark-charcoal bg-dark-charcoal/10 backdrop-blur text-dark-charcoal text-xs font-bold uppercase tracking-[0.2em]">
            {{ app()->getLocale() === 'tr' ? 'Sektöre Özel' : 'Industry Solutions' }}
        </div>

        <h1 class="text-4xl md:text-5xl font-bold text-dark-charcoal font-heading leading-tight mb-6">
            {{ app()->getLocale() === 'tr' ? 'Sektöre Göre Çözümler' : 'Solutions by Segment' }}
        </h1>

        <p class="text-xl text-dark-charcoal/80 max-w-3xl font-light leading-relaxed">
            {{ app()->getLocale() === 'tr' ? 'Kafe, otel, fast-food, catering ve etkinlik operasyonları için ürün kombinasyonlarını tek tedarik modeliyle planlıyoruz.' : 'We plan product bundles for cafe, hotel, fast-food, catering and event operations with a single-supplier model.' }}
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
                                'cafe' => 'images/lifestyle-iced-coffee.jpg',
                                'fastfood' => 'images/product-kraft-bag.png',
                                'hotel' => 'images/catalog/asset-19.jpg',
                                'event' => 'images/lifestyle-cocktail.jpg',
                                'retail' => 'images/social-coffee.png',
                            ];
                            $imageSrc = asset($bgImages[$solution['key']] ?? 'images/lifestyle-drinks.png');
                        @endphp
                        <img src="{{ $imageSrc }}" alt="Lunar Ambalaj Solution" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
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
                            {{ app()->getLocale() === 'tr' ? $solution['title_tr'] : $solution['title_en'] }}
                        </h2>

                        <p class="text-slate-600 leading-relaxed mb-4 text-sm flex-1">
                            {{ app()->getLocale() === 'tr' ? $solution['body_tr'] : $solution['body_en'] }}
                        </p>

                        <!-- Suggested Set -->
                        <div class="pt-4 border-t border-slate-200 mt-4">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                                {{ app()->getLocale() === 'tr' ? 'Önerilen Set:' : 'Suggested Set:' }}
                            </p>
                            <p class="text-sm font-medium text-slate-800">
                                {{ app()->getLocale() === 'tr' ? $solution['set_tr'] : $solution['set_en'] }}
                            </p>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-6">
                            <a href="{{ route(app()->getLocale() . '.quote') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-yellow text-dark-charcoal font-bold text-sm uppercase tracking-wide hover:bg-primary-yellow/90 transition-colors shadow-md group-hover:shadow-lg">
                                {{ app()->getLocale() === 'tr' ? 'Hızlı Teklif Al' : 'Get Fast Quote' }}
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
                {{ app()->getLocale() === 'tr' ? 'Süreç' : 'Process' }}
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 font-heading uppercase">
                {{ app()->getLocale() === 'tr' ? 'Nasıl Çalışıyoruz?' : 'How We Work' }}
            </h2>
        </div>

        <div class="grid md:grid-cols-4 gap-6">
            @php
                $steps = [
                    ['num' => '01', 'title_tr' => 'İhtiyaç Analizi', 'title_en' => 'Requirement Analysis', 'desc_tr' => 'Kategori, adet ve kullanım senaryonuzu değerlendiriyoruz', 'desc_en' => 'We evaluate your category, quantity and usage scenarios'],
                    ['num' => '02', 'title_tr' => 'Numune / Onay', 'title_en' => 'Sampling / Approval', 'desc_tr' => 'Baskı proofları ve ürün numuneleriyle önizleme süreci', 'desc_en' => 'Preview process with print proofs and product samples'],
                    ['num' => '03', 'title_tr' => 'Üretim Planlama', 'title_en' => 'Production Planning', 'desc_tr' => 'Termin ve sevkiyat takvimi netleştirilir', 'desc_en' => 'Lead-time and shipment calendar confirmed'],
                    ['num' => '04', 'title_tr' => 'Sevkiyat ve Takip', 'title_en' => 'Shipment & Follow-up', 'desc_tr' => 'Teslimat sonrası destek ve stok yönetimi', 'desc_en' => 'Post-delivery support and stock management'],
                ];
            @endphp

            @foreach($steps as $step)
                <div class="relative bg-slate-50 p-6 border-l-4 border-primary-yellow hover:shadow-lg transition-all" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="text-6xl font-black text-primary-yellow/20 absolute top-2 right-4">
                        {{ $step['num'] }}
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            {{ app()->getLocale() === 'tr' ? $step['title_tr'] : $step['title_en'] }}
                        </h3>
                        <p class="text-sm text-slate-600">
                            {{ app()->getLocale() === 'tr' ? $step['desc_tr'] : $step['desc_en'] }}
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
            {{ app()->getLocale() === 'tr' ? 'Özel Çözüm Paketi Hazırlayalım' : 'Let\'s Prepare a Custom Solution Package' }}
        </h2>
        <p class="text-lg text-dark-charcoal/80 mb-8 max-w-2xl mx-auto">
            {{ app()->getLocale() === 'tr' ? 'Proje bazlı taleplerde numune, termin ve sevkiyat planlaması ile ilerliyoruz. Kategori setinizi belirtin, 24 saat içinde detaylı teklif alalım.' : 'Project-based requests are handled with sampling, lead-time and shipment planning. Specify your category set and get a detailed quote within 24 hours.' }}
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route(app()->getLocale() . '.quote') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-dark-charcoal text-white font-bold text-sm uppercase tracking-wide hover:bg-dark-charcoal/90 transition-colors shadow-lg">
                {{ app()->getLocale() === 'tr' ? '24 Saatte Teklif Alın' : 'Get Quote in 24h' }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
            <a href="{{ route(app()->getLocale() . '.products') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-white text-dark-charcoal font-bold text-sm uppercase tracking-wide hover:bg-slate-100 transition-colors shadow-lg">
                {{ app()->getLocale() === 'tr' ? 'Ürünleri İncele' : 'Browse Products' }}
            </a>
        </div>
    </div>
</section>

@endsection
