@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<x-hero
    :subtitle="__('site.products.subtitle')"
    :title="__('site.products.hero_title')"
    height="min-h-[400px]"
>
    <p class="text-xl text-slate-300 mb-10 max-w-2xl font-light leading-relaxed">
        {{ __('site.products.hero_subtitle') }}
    </p>
</x-hero>

<!-- Category Filters -->
<section class="bg-white py-8 border-b border-slate-200 sticky top-[72px] z-30 shadow-sm">
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route(app()->getLocale() . '.products') }}"
               class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold uppercase tracking-wide transition-all {{ $activeCategorySlug === '' ? 'bg-primary-yellow text-dark-charcoal shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)]' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                {{ __('site.products.all_categories') }}
            </a>
            @foreach($categories as $category)
                @php $ct = $category->translation(app()->getLocale()); @endphp
                @if($ct)
                    <a href="{{ route(app()->getLocale() . '.products', ['category' => $ct->slug]) }}"
                       class="px-6 py-2.5 text-sm font-bold uppercase tracking-wide transition-all {{ $activeCategorySlug === $ct->slug ? 'bg-primary-yellow text-dark-charcoal shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)]' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        {{ $ct->name }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="py-16 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4">
        @if($products->count() > 0)
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($products as $product)
                    @php
                        $t = $product->translation(app()->getLocale());
                        $categoryName = $product->category?->translation(app()->getLocale())?->name;
                    @endphp
                    @if($t)
                        <x-card.product :product="$product" :locale="app()->getLocale()" />
                    @endif
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-lg text-slate-600">
                    {{ __('site.products.no_products') }}
                </p>
            </div>
        @endif
    </div>
</section>

<!-- Order Planning Info -->
<section class="py-16 bg-white">
    <div class="mx-auto max-w-7xl px-4">
        <div class="bg-slate-50 p-8 border-l-4 border-primary-yellow">
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <svg class="w-12 h-12 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 font-heading uppercase mb-3">
                        {{ __('site.products.order_note_title') }}
                    </h2>
                    <p class="text-slate-700 leading-relaxed mb-4">
                        {{ __('site.products.order_note_text') }}
                    </p>
                    <x-button variant="primary" :href="route(app()->getLocale() . '.quote')">
                        {{ __('site.cta_quote') }}
                        <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- GTM Event -->
<script>
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({
    event: 'view_item_list',
    item_list_name: '{{ __('site.products.list_name') }}',
    item_count: {{ $products->count() }}
});
</script>

@endsection
