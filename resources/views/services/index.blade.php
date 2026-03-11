@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <h1 class="text-3xl font-bold">{{ __('site.menu.services') }}</h1>
    <p class="mt-3 max-w-3xl text-slate-600">
        {{ __('site.services.hero_subtitle') }}
    </p>

    <div class="mt-8 grid gap-4 md:grid-cols-2">
        @foreach($services as $service)
            @php
                $t = $service->translation(app()->getLocale());
                $fallbackIcons = [
                    1 => 'images/service-manufacturing.svg',
                    2 => 'images/service-printing.svg',
                    3 => 'images/service-wrapping.svg',
                    4 => 'images/service-cup.svg',
                    5 => 'images/service-wrapping.svg',
                    6 => 'images/service-printing.svg',
                ];
                $serviceImage = $service->icon;
                if (!$serviceImage || str_contains($serviceImage, 'images/catalog/asset-')) {
                    $serviceImage = $fallbackIcons[$service->order] ?? 'images/service-manufacturing.svg';
                }
                $serviceBullets = [
                    1 => [__('site.services.bullet1_1'), __('site.services.bullet1_2'), __('site.services.bullet1_3')],
                    2 => [__('site.services.bullet2_1'), __('site.services.bullet2_2'), __('site.services.bullet2_3')],
                    3 => [__('site.services.bullet3_1'), __('site.services.bullet3_2'), __('site.services.bullet3_3')],
                    4 => [__('site.services.bullet4_1'), __('site.services.bullet4_2'), __('site.services.bullet4_3')],
                    5 => [__('site.services.bullet5_1'), __('site.services.bullet5_2'), __('site.services.bullet5_3')],
                    6 => [__('site.services.bullet6_1'), __('site.services.bullet6_2'), __('site.services.bullet6_3')],
                ];
            @endphp
            @if($t)
                <article class="rounded-xl border border-slate-200 bg-white p-6">
                    <img
                        loading="lazy"
                        src="{{ asset($serviceImage) }}"
                        alt="{{ $t->title }}"
                        class="mb-4 h-44 w-full rounded-lg object-cover"
                        width="704"
                        height="352"
                        decoding="async"
                    >
                    <h3 class="text-xl font-semibold">{{ $t->title }}</h3>
                    <p class="mt-2 text-slate-600">{{ $t->body }}</p>
                    <ul class="mt-4 space-y-1 text-sm text-slate-600">
                        @foreach(($serviceBullets[$service->order] ?? []) as $item)
                            <li>- {{ $item }}</li>
                        @endforeach
                    </ul>
                </article>
            @endif
        @endforeach
    </div>

    <div class="mt-12 rounded-xl border border-amber-200 bg-amber-50 p-6">
        <h2 class="text-xl font-semibold text-slate-900">{{ __('site.services.how_we_work') }}</h2>
        <div class="mt-4 grid gap-3 text-sm md:grid-cols-4">
            <div class="rounded bg-white p-3">1. {{ __('site.services.step1') }}</div>
            <div class="rounded bg-white p-3">2. {{ __('site.services.step2') }}</div>
            <div class="rounded bg-white p-3">3. {{ __('site.services.step3') }}</div>
            <div class="rounded bg-white p-3">4. {{ __('site.services.step4') }}</div>
        </div>
    </div>
</section>
@endsection
