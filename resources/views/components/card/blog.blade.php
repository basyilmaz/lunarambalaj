@props(['post', 'locale' => 'tr'])

@php
    $translation = $post->translation($locale);
@endphp

@if($translation)
<article class="group flex h-full flex-col overflow-hidden bg-white shadow-sm transition-all duration-300 hover:shadow-xl" data-aos="fade-up">
    <div class="relative h-56 overflow-hidden bg-slate-100">
        <img
            src="{{ $post->cover ? asset($post->cover) : asset('images/hero-bg.webp') }}"
            alt="{{ $translation->title }}"
            loading="lazy"
            width="960"
            height="540"
            decoding="async"
            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-dark-charcoal/60 to-transparent"></div>

        <div class="absolute bottom-4 left-4 bg-primary-yellow px-3 py-2 text-xs font-bold text-dark-charcoal">
            <div class="text-2xl leading-none">{{ $post->published_at->format('d') }}</div>
            <div class="uppercase">{{ $post->published_at->format('M Y') }}</div>
        </div>
    </div>

    <div class="flex flex-grow flex-col p-6">
        <h3 class="mb-3 line-clamp-2 font-heading text-xl font-bold uppercase text-slate-900 transition-colors group-hover:text-primary-yellow">
            {{ $translation->title }}
        </h3>

        @if($translation->short_desc)
            <p class="mb-4 flex-grow line-clamp-3 text-sm leading-relaxed text-text-gray">
                {{ $translation->short_desc }}
            </p>
        @endif

        <a href="{{ route($locale . '.blog.show', $translation->slug) }}"
           class="inline-flex items-center gap-2 text-sm font-bold uppercase text-primary-yellow transition-all group-hover:gap-4">
            @php
                $readMoreText = [
                    'tr' => 'Devamını Oku',
                    'en' => 'Read More',
                    'ru' => 'Читать далее',
                    'ar' => 'اقرأ المزيد',
                    'es' => 'Leer Más',
                ];
            @endphp
            {{ $readMoreText[$locale] ?? $readMoreText['en'] }}
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</article>
@endif
