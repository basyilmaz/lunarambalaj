@extends('layouts.app')

@section('content')

<!-- Hero Slider -->
<section class="hero-swiper swiper relative">
    <div class="swiper-wrapper">
        @foreach($heroSlides as $slide)
            <div class="swiper-slide">
                <div class="relative bg-slate-900 overflow-hidden min-h-[700px] flex items-center">
                    <!-- Background Image -->
                    <div class="absolute inset-0">
                        <img
                            src="{{ asset('images/hero-bg.png') }}"
                            alt="Lunar Ambalaj"
                            class="w-full h-full object-cover opacity-50 blur-[2px]"
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

<!-- Product Categories -->
<section class="py-24 bg-white">
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
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0"
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
            <div class="categories-prev absolute left-0 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-primary-yellow hover:bg-dark-charcoal text-dark-charcoal hover:text-white flex items-center justify-center cursor-pointer transition-colors -ml-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </div>
            <div class="categories-next absolute right-0 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-primary-yellow hover:bg-dark-charcoal text-dark-charcoal hover:text-white flex items-center justify-center cursor-pointer transition-colors -mr-6">
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
<section class="py-24 bg-dark-charcoal">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="app()->getLocale() === 'tr' ? 'Neler Yapıyoruz' : 'What We Do'"
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
<section class="py-24 bg-white">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="app()->getLocale() === 'tr' ? 'Sektörel Çözümler' : 'Industry Solutions'"
            :title="app()->getLocale() === 'tr' ? 'İşletmenize Özel Paketler' : 'Tailored Packages for Your Business'"
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
                {{ app()->getLocale() === 'tr' ? 'Tüm Çözümleri Gör' : 'View All Solutions' }}
            </x-button>
        </div>
    </div>
</section>

<!-- Testimonials Section (placeholder for when testimonials are added) -->
@if(count($testimonials) > 0)
<section class="py-24 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="app()->getLocale() === 'tr' ? 'Referanslarımız' : 'Our References'"
            :title="app()->getLocale() === 'tr' ? 'Müşterilerimiz Ne Diyor?' : 'What Our Clients Say'"
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
            <div class="testimonials-prev absolute left-0 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-primary-yellow hover:bg-dark-charcoal text-dark-charcoal hover:text-white flex items-center justify-center cursor-pointer transition-colors -ml-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </div>
            <div class="testimonials-next absolute right-0 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-primary-yellow hover:bg-dark-charcoal text-dark-charcoal hover:text-white flex items-center justify-center cursor-pointer transition-colors -mr-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>

            <!-- Pagination -->
            <div class="testimonials-pagination mt-8 text-center"></div>
        </div>
    </div>
</section>
@endif

<!-- Lifestyle & Showcase Section -->
<section class="py-24 bg-gradient-to-br from-slate-50 via-white to-slate-50">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="app()->getLocale() === 'tr' ? 'Ürünlerimiz Hayatınızda' : 'Our Products in Your Life'"
            :title="app()->getLocale() === 'tr' ? 'Kalite ve Tasarım Bir Arada' : 'Quality Meets Design'"
            align="center"
        />

        <div class="grid md:grid-cols-2 gap-8 mt-12">
            <!-- Left: Product Showcase -->
            <div class="grid grid-cols-2 gap-4" data-aos="fade-right">
                <div class="space-y-4">
                    <div class="overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-500 group">
                        <img src="{{ asset('images/product-cup-colorful.png') }}" alt="Lunar Ambalaj Bardak" loading="lazy" width="640" height="640" decoding="async" class="w-full h-auto object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-500 group">
                        <img src="{{ asset('images/lifestyle-cocktail.jpg') }}" alt="Lunar Pipet" loading="lazy" width="640" height="640" decoding="async" class="w-full h-auto object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                </div>
                <div class="space-y-4 pt-12">
                    <div class="overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-500 group">
                        <img src="{{ asset('images/product-kraft-bag.png') }}" alt="Lunar Ambalaj Kraft Torba" loading="lazy" width="640" height="640" decoding="async" class="w-full h-auto object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-500 group">
                        <img src="{{ asset('images/product-cup-white.png') }}" alt="Lunar Bardak Beyaz" loading="lazy" width="640" height="640" decoding="async" class="w-full h-auto object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                </div>
            </div>

            <!-- Right: Lifestyle Images & Text -->
            <div class="flex flex-col justify-center" data-aos="fade-left">
                <div class="mb-8 overflow-hidden rounded-lg shadow-2xl">
                    <img src="{{ asset('images/lifestyle-iced-coffee.jpg') }}" alt="Lunar Ambalaj Ürünleri" loading="lazy" width="1200" height="800" decoding="async" class="w-full h-auto object-cover">
                </div>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-yellow rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-dark-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 font-heading uppercase mb-1">
                                {{ app()->getLocale() === 'tr' ? 'Kurumsal Baskı' : 'Corporate Printing' }}
                            </h3>
                            <p class="text-text-gray text-sm">
                                {{ app()->getLocale() === 'tr' ? 'Logonuz ve markanız tüm ürünlerde' : 'Your logo and brand on all products' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-yellow rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-dark-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 font-heading uppercase mb-1">
                                {{ app()->getLocale() === 'tr' ? 'Hızlı Üretim' : 'Fast Production' }}
                            </h3>
                            <p class="text-text-gray text-sm">
                                {{ app()->getLocale() === 'tr' ? '15 gün ortalama termin süresi' : '15-day average lead time' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-yellow rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-dark-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 font-heading uppercase mb-1">
                                {{ app()->getLocale() === 'tr' ? 'Kalite Garantisi' : 'Quality Guarantee' }}
                            </h3>
                            <p class="text-text-gray text-sm">
                                {{ app()->getLocale() === 'tr' ? 'ISO 9001 sertifikalı üretim' : 'ISO 9001 certified production' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <x-button variant="primary" :href="route(app()->getLocale() . '.products')">
                        {{ app()->getLocale() === 'tr' ? 'Tüm Ürünleri Keşfedin' : 'Explore All Products' }}
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section class="py-24 bg-white">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="app()->getLocale() === 'tr' ? 'Blog ve Haberler' : 'Blog & News'"
            :title="app()->getLocale() === 'tr' ? 'Son Yazılar' : 'Latest Articles'"
            align="center"
        />

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 mt-12">
            @foreach($posts as $post)
                <x-card.blog :post="$post" :locale="app()->getLocale()" />
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <x-button variant="outline" :href="route(app()->getLocale() . '.blog')">
                {{ app()->getLocale() === 'tr' ? 'Tüm Yazıları Gör' : 'View All Articles' }}
            </x-button>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="py-20 bg-primary-yellow">
    <div class="mx-auto max-w-7xl px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-dark-charcoal font-heading uppercase mb-6" data-aos="fade-up">
            {{ app()->getLocale() === 'tr' ? 'Ambalaj İhtiyacınız İçin Hemen Teklif Alın' : 'Get Your Quote for Packaging Needs Now' }}
        </h2>

        <p class="text-lg text-dark-charcoal/80 mb-10 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            {{ app()->getLocale() === 'tr' ? '24 saat içinde özel fiyat teklifi ve hızlı termin bilgisi' : 'Get custom pricing and fast lead time information within 24 hours' }}
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
