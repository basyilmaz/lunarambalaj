@props(['service', 'locale' => 'tr'])

@php
$translation = $service->translation($locale);
@endphp

@if($translation)
<div class="group bg-white p-8 shadow-sm border-b-4 border-transparent hover:border-primary-yellow transition-all duration-300" data-aos="fade-up">
    <!-- Service Icon -->
    @if($service->icon)
        <div class="mb-6 w-16 h-16">
            <img
                src="{{ asset($service->icon) }}"
                alt="{{ $translation->title }}"
                class="w-full h-full object-contain opacity-80 group-hover:opacity-100 transition-opacity"
                width="64"
                height="64"
                decoding="async"
            >
        </div>
    @else
        <div class="mb-6 w-16 h-16 bg-primary-yellow/10 rounded-full flex items-center justify-center group-hover:bg-primary-yellow/20 transition-colors">
            <svg class="w-8 h-8 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
    @endif

    <!-- Service Title -->
    <h3 class="text-2xl font-bold text-slate-900 font-heading uppercase mb-4 group-hover:text-primary-yellow transition-colors">
        {{ $translation->title }}
    </h3>

    <!-- Service Description -->
    @if($translation->description)
        <p class="text-text-gray leading-relaxed mb-4">
            {{ $translation->description }}
        </p>
    @endif

    <!-- Service Features (if available as array in description) -->
    {{ $slot }}
</div>
@endif
