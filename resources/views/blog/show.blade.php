@extends('layouts.app')

@section('content')

<!-- Hero Image with Title Overlay -->
<section class="relative bg-slate-900 min-h-[500px] flex items-end overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0">
        @if($post->cover)
            <img
                src="{{ asset($post->cover) }}"
                alt="{{ $translation->title }}"
                class="w-full h-full object-cover opacity-40"
                width="1920"
                height="1080"
                loading="eager"
                fetchpriority="high"
                decoding="async"
            >
        @else
            <img
                src="{{ asset('images/hero-bg.webp') }}"
                alt="{{ $translation->title }}"
                class="w-full h-full object-cover opacity-30"
                width="1920"
                height="1080"
                loading="eager"
                fetchpriority="high"
                decoding="async"
            >
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/70 to-transparent"></div>
    </div>

    <!-- Title Overlay -->
    <div class="relative max-w-4xl mx-auto px-4 py-16 w-full">
        <!-- Category Badge -->
        <div class="inline-block mb-4 px-4 py-1.5 border-l-4 border-primary-yellow bg-white/10 backdrop-blur text-white text-xs font-bold uppercase tracking-[0.2em]">
            {{ __('site.blog.badge') }}
        </div>

        <!-- Title -->
        <h1 class="text-4xl md:text-5xl font-bold text-white font-heading leading-tight mb-6">
            {{ $translation->title }}
        </h1>

        <!-- Meta Info -->
        <div class="flex flex-wrap items-center gap-6 text-slate-300">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ optional($post->published_at)->format('d.m.Y') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ __('site.blog.read_time') }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Article Content -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-4 gap-12">
            <!-- Main Content -->
            <article class="lg:col-span-3">
                <!-- Short Description -->
                @if($translation->short_desc)
                    <div class="text-xl text-slate-600 leading-relaxed mb-8 pb-8 border-b border-slate-200">
                        {{ $translation->short_desc }}
                    </div>
                @endif

                <!-- Body Content -->
                <div class="prose prose-slate prose-lg max-w-none">
                    {!! nl2br(e($translation->body)) !!}
                </div>

                <!-- Social Share -->
                <div class="mt-12 pt-8 border-t border-slate-200">
                    <p class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4">
                        {{ __('site.blog.share_title') }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <!-- WhatsApp -->
                        <a href="https://wa.me/?text={{ urlencode($translation->title . ' - ' . url()->current()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-success-green text-white rounded hover:bg-success-green/90 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            WhatsApp
                        </a>

                        <!-- LinkedIn -->
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-info-blue text-white rounded hover:bg-info-blue/90 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            LinkedIn
                        </a>

                        <!-- Copy Link -->
                        <button onclick="copyToClipboard('{{ url()->current() }}')" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-200 text-slate-900 rounded hover:bg-slate-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            {{ __('site.blog.copy_link') }}
                        </button>
                    </div>
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="lg:col-span-1 space-y-8">
                <!-- Quick Links -->
                <div class="bg-slate-50 p-6 rounded-lg border-l-4 border-primary-yellow sticky top-24">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4">
                        {{ __('site.blog.related_topics') }}
                    </h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-primary-yellow flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="text-slate-600">{{ __('site.blog.moq_planning') }}</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-primary-yellow flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="text-slate-600">{{ __('site.blog.print_prep') }}</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-primary-yellow flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="text-slate-600">{{ __('site.blog.category_selection') }}</span>
                        </li>
                    </ul>

                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <p class="text-sm text-slate-600 mb-3">
                            {{ __('site.blog.quote_topic') }}
                        </p>
                        <x-button variant="primary" :href="route(app()->getLocale() . '.quote')" class="w-full justify-center" size="sm">
                            {{ __('site.blog.get_quote') }}
                        </x-button>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Related Posts -->
@if(isset($relatedPosts) && $relatedPosts->count() > 0)
<section class="py-16 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="__('site.blog.related_content')"
            :title="__('site.blog.you_may_like')"
            align="left"
        />

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 mt-12">
            @foreach($relatedPosts as $relatedPost)
                <x-card.blog :post="$relatedPost" :locale="app()->getLocale()" />
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16 bg-primary-yellow">
    <div class="mx-auto max-w-4xl px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-dark-charcoal font-heading uppercase mb-4">
            {{ __('site.blog.content_to_action') }}
        </h2>
        <p class="text-lg text-dark-charcoal/80 mb-8 max-w-2xl mx-auto">
            {{ __('site.blog.combine_categories') }}
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <x-button variant="secondary" :href="route(app()->getLocale() . '.quote')" size="lg">
                {{ __('site.cta_quote') }}
            </x-button>
            <x-button variant="ghost" :href="route(app()->getLocale() . '.products')" size="lg" class="!text-dark-charcoal hover:!text-white hover:!bg-dark-charcoal/10">
                {{ __('site.blog.browse_products') }}
            </x-button>
        </div>
    </div>
</section>

<!-- Copy to Clipboard Script -->
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const originalText = event.target.innerText;
        event.target.innerText = '{{ __('site.blog.copied') }}';
        setTimeout(() => {
            event.target.innerText = originalText;
        }, 2000);
    });
}
</script>

@endsection
