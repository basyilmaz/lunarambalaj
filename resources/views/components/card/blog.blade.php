@props(['post', 'locale' => 'tr'])

@php
$translation = $post->translation($locale);
@endphp

@if($translation)
<article class="group bg-white shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden h-full flex flex-col" data-aos="fade-up">
    <!-- Featured Image -->
    <div class="relative h-56 overflow-hidden bg-slate-100">
        <img
            src="{{ $post->image ? asset($post->image) : asset('images/hero-bg.png') }}"
            alt="{{ $translation->title }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-dark-charcoal/60 to-transparent"></div>

        <!-- Date Badge -->
        <div class="absolute bottom-4 left-4 bg-primary-yellow text-dark-charcoal px-3 py-2 text-xs font-bold">
            <div class="text-2xl leading-none">{{ $post->published_at->format('d') }}</div>
            <div class="uppercase">{{ $post->published_at->format('M Y') }}</div>
        </div>
    </div>

    <!-- Post Content -->
    <div class="p-6 flex-grow flex flex-col">
        <h3 class="text-xl font-bold text-slate-900 font-heading uppercase mb-3 group-hover:text-primary-yellow transition-colors line-clamp-2">
            {{ $translation->title }}
        </h3>

        @if($translation->short_desc)
            <p class="text-text-gray text-sm leading-relaxed mb-4 flex-grow line-clamp-3">
                {{ $translation->short_desc }}
            </p>
        @endif

        <!-- Read More -->
        <a href="{{ route($locale . '.blog.show', $translation->slug) }}"
           class="inline-flex items-center gap-2 text-primary-yellow font-bold text-sm uppercase group-hover:gap-4 transition-all">
            @php
                $readMoreText = [
                    'tr' => 'Devamını Oku',
                    'en' => 'Read More',
                    'ru' => 'Читать далее',
                    'ar' => 'اقرأ المزيد',
                ];
            @endphp
            {{ $readMoreText[$locale] ?? $readMoreText['en'] }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</article>
@endif
