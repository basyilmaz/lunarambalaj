@props(['service', 'locale' => 'tr'])

@php
$translation = $service->translation($locale);
$imagePath = $service->icon;
if (!$imagePath) {
    $imagePath = match ($service->order) {
        1 => 'images/catalog/asset-07.png',
        2 => 'images/catalog/asset-11.jpg',
        3 => 'images/catalog/asset-17.jpg',
        4 => 'images/catalog/asset-14.jpg',
        5 => 'images/catalog/asset-20.jpg',
        6 => 'images/catalog/asset-22.jpg',
        default => 'images/hero-bg.webp',
    };
}
@endphp

@if($translation)
<article class="group overflow-hidden bg-white shadow-sm border-b-4 border-transparent hover:border-primary-yellow transition-all duration-300" data-aos="fade-up">
    <div class="relative h-52 overflow-hidden bg-slate-100">
        <img
            src="{{ asset($imagePath) }}"
            alt="{{ $translation->title }}"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            loading="lazy"
            width="672"
            height="416"
            decoding="async"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 to-transparent"></div>
        <div class="absolute bottom-4 left-4">
            <span class="inline-flex items-center rounded-sm bg-primary-yellow px-3 py-1 text-xs font-bold uppercase tracking-wider text-dark-charcoal">
                {{ __('site.services.subtitle') }}
            </span>
        </div>
    </div>

    <div class="p-8">
        <div class="mb-6 w-14 h-14 rounded-full bg-primary-yellow/10 flex items-center justify-center">
            <img
                src="{{ asset('images/service-printing.svg') }}"
                alt="{{ $translation->title }}"
                class="w-8 h-8 object-contain opacity-80"
                width="32"
                height="32"
                decoding="async"
            >
        </div>

        <h3 class="text-2xl font-bold text-slate-900 font-heading uppercase mb-4 group-hover:text-primary-yellow transition-colors">
            {{ $translation->title }}
        </h3>

        @if($translation->body)
            <p class="text-text-gray leading-relaxed mb-4">
                {{ $translation->body }}
            </p>
        @endif

        {{ $slot }}
    </div>
</article>
@endif
