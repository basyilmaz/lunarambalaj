@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
$baseClasses = 'inline-flex items-center justify-center font-bold uppercase tracking-widest transition-all duration-300 border-0';

$variantClasses = [
    'primary' => 'bg-primary-yellow text-dark-charcoal hover:bg-white hover:text-slate-900 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-y-1',
    'secondary' => 'bg-dark-charcoal text-white hover:bg-primary-yellow hover:text-dark-charcoal',
    'outline' => 'border-2 border-primary-yellow text-primary-yellow hover:bg-primary-yellow hover:text-dark-charcoal',
    'ghost' => 'text-slate-900 hover:text-primary-yellow',
];

$sizeClasses = [
    'sm' => 'px-6 py-2 text-xs',
    'md' => 'px-10 py-4 text-sm',
    'lg' => 'px-12 py-5 text-base',
];

$classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
