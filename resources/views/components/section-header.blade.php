@props([
    'subtitle' => '',
    'title' => '',
    'description' => '',
    'align' => 'center',
])

<div {{ $attributes->merge(['class' => ($align === 'center' ? 'text-center mx-auto max-w-3xl' : '') . ' mb-12']) }} data-aos="fade-up">
    @if($subtitle)
        <span class="text-primary-yellow font-bold tracking-widest uppercase text-sm mb-2 block">{{ $subtitle }}</span>
    @endif

    @if($title)
        <h2 class="text-4xl md:text-5xl font-bold text-slate-900 font-heading uppercase leading-tight mb-4">
            {{ $title }}
        </h2>
    @endif

    @if($description)
        <p class="text-lg text-text-gray leading-relaxed">
            {{ $description }}
        </p>
    @endif

    {{ $slot }}
</div>
