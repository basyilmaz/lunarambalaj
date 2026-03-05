@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<x-hero
    :subtitle="app()->getLocale() === 'tr' ? 'Blog ve Haberler' : 'Blog & News'"
    :title="app()->getLocale() === 'tr' ? 'Ambalaj Trendleri ve B2B Çözüm Rehberleri' : 'Packaging Trends and B2B Solution Guides'"
    height="min-h-[400px]"
>
    <p class="text-xl text-slate-300 mb-10 max-w-2xl font-light leading-relaxed">
        {{ app()->getLocale() === 'tr' ? 'Ambalaj trendleri, baskı planlaması, kategori seçimi ve toplu sipariş operasyonlarını destekleyen B2B içerikler.' : 'B2B content on packaging trends, print planning, category selection and bulk-order operations.' }}
    </p>
</x-hero>

<!-- Content Categories -->
<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-6 md:grid-cols-3">
            <div class="bg-white p-6 border-l-4 border-primary-yellow" data-aos="fade-up">
                <svg class="w-10 h-10 text-primary-yellow mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-bold text-slate-900 uppercase tracking-wide text-sm">
                    {{ app()->getLocale() === 'tr' ? 'Markalaşma Odaklı Ürün Rehberleri' : 'Brand-Focused Product Guides' }}
                </p>
            </div>
            <div class="bg-white p-6 border-l-4 border-info-blue" data-aos="fade-up" data-aos-delay="100">
                <svg class="w-10 h-10 text-info-blue mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-bold text-slate-900 uppercase tracking-wide text-sm">
                    {{ app()->getLocale() === 'tr' ? 'MOQ, Termin ve Tedarik Planlama' : 'MOQ, Lead-Time and Supply Planning' }}
                </p>
            </div>
            <div class="bg-white p-6 border-l-4 border-success-green" data-aos="fade-up" data-aos-delay="200">
                <svg class="w-10 h-10 text-success-green mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="font-bold text-slate-900 uppercase tracking-wide text-sm">
                    {{ app()->getLocale() === 'tr' ? 'Sektör Bazlı Çözüm Senaryoları' : 'Segment-Based Solution Scenarios' }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Blog Posts Grid -->
<section class="py-16 bg-white">
    <div class="mx-auto max-w-7xl px-4">
        @if($posts->count() > 0)
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    @php $t = $post->translation(app()->getLocale()); @endphp
                    @if($t)
                        <x-card.blog :post="$post" :locale="app()->getLocale()" />
                    @endif
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-lg text-slate-600">
                    {{ app()->getLocale() === 'tr' ? 'Henüz blog yazısı bulunmuyor.' : 'No blog posts available yet.' }}
                </p>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-dark-charcoal">
    <div class="mx-auto max-w-7xl px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white font-heading uppercase mb-4" data-aos="fade-up">
            {{ app()->getLocale() === 'tr' ? 'İçerikten Uygulamaya Geçin' : 'Move from Content to Execution' }}
        </h2>
        <p class="text-lg text-slate-300 mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            {{ app()->getLocale() === 'tr' ? 'Okuduğunuz konuya uygun ürün kategorilerini tek teklif dosyasında birleştirmek için hızlı teklif oluşturun.' : 'Create a fast quote to combine relevant product categories in a single quotation workflow.' }}
        </p>
        <div class="flex flex-wrap gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
            <x-button variant="primary" :href="route(app()->getLocale() . '.quote')" size="lg">
                {{ app()->getLocale() === 'tr' ? '24 Saatte Teklif Alın' : 'Get Quote Within 24 Hours' }}
            </x-button>
            <x-button variant="outline" :href="route(app()->getLocale() . '.products')" size="lg" class="!border-slate-500 !text-white hover:!border-white hover:!bg-white/10">
                {{ app()->getLocale() === 'tr' ? 'Ürünleri İncele' : 'Browse Products' }}
            </x-button>
        </div>
    </div>
</section>

@endsection
