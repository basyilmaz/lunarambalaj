@props([
    'title' => '',
    'subtitle' => '',
    'backgroundImage' => 'images/hero-bg.webp',
    'cta1' => null,
    'cta2' => null,
    'height' => 'min-h-[700px]',
])

<section class="relative bg-slate-900 overflow-hidden {{ $height }} flex items-center">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0">
        <img
            src="{{ asset($backgroundImage) }}"
            alt="Lunar Ambalaj"
            width="1920"
            height="1080"
            loading="eager"
            fetchpriority="high"
            decoding="async"
            class="w-full h-full object-cover opacity-50 blur-[2px]"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/70 to-transparent"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 w-full py-20">
        <div class="max-w-3xl">
            @if($subtitle)
                <div class="inline-block mb-6 px-4 py-1.5 border-l-4 border-primary-yellow bg-white/10 backdrop-blur text-white text-xs font-bold uppercase tracking-[0.2em]">
                    {{ $subtitle }}
                </div>
            @endif

            <h1 class="text-5xl md:text-7xl font-bold text-white mb-8 font-heading leading-none uppercase tracking-tight">
                {{ $title }}
            </h1>

            {{ $slot }}

            @if($cta1 || $cta2)
                <div class="flex flex-wrap gap-5 mt-10">
                    @if($cta1)
                        <x-button
                            variant="primary"
                            :href="$cta1['href'] ?? '#'"
                            size="lg"
                        >
                            {{ $cta1['text'] ?? 'Learn More' }}
                        </x-button>
                    @endif

                    @if($cta2)
                        <x-button
                            variant="outline"
                            :href="$cta2['href'] ?? '#'"
                            size="lg"
                            class="!border-slate-500 !text-white hover:!border-white hover:!bg-white/10"
                        >
                            {{ $cta2['text'] ?? 'Contact' }}
                        </x-button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
