@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <h1 class="text-3xl font-bold">{{ app()->getLocale()==='tr' ? 'Hizmetler' : 'Services' }}</h1>
    <p class="mt-3 max-w-3xl text-slate-600">
        {{ app()->getLocale()==='tr' ? 'Üretimden baskıya, tekli paketlemeden bardak baskıya kadar markanızın sahadaki görünürlüğünü ve servis kalitesini artıran B2B çözümler sunuyoruz.' : 'From manufacturing to printing, wrapping and cup branding, we provide B2B solutions that increase your field visibility and service quality.' }}
    </p>

    <div class="mt-8 grid gap-4 md:grid-cols-2">
        @foreach($services as $service)
            @php
                $t = $service->translation(app()->getLocale());
                $serviceImage = $service->icon ?: 'images/service-manufacturing.svg';
                $serviceBullets = [
                    1 => [
                        app()->getLocale()==='tr' ? 'Farklı çap ve boy seçenekleri' : 'Multiple diameter and length options',
                        app()->getLocale()==='tr' ? 'Gıda temasına uygun üretim' : 'Food-contact compliant production',
                        app()->getLocale()==='tr' ? 'Düzenli B2B sevkiyat planı' : 'Planned B2B shipment schedule',
                    ],
                    2 => [
                        app()->getLocale()==='tr' ? 'Kurumsal renklere uygun baskı' : 'Brand-color matched printing',
                        app()->getLocale()==='tr' ? 'Tek renk / çok renk seçeneği' : 'Single and multi-color options',
                        app()->getLocale()==='tr' ? 'Numune ile ön onay süreci' : 'Pre-approval sample process',
                    ],
                    3 => [
                        app()->getLocale()==='tr' ? 'Tekli paketleme (jelatin)' : 'Individual wrapping',
                        app()->getLocale()==='tr' ? 'Hijyen odaklı servis desteği' : 'Hygiene-focused service support',
                        app()->getLocale()==='tr' ? 'Operasyon akışını hızlandıran çözüm' : 'Operation-friendly format',
                    ],
                    4 => [
                        app()->getLocale()==='tr' ? 'Bardak üzerinde marka görünürlüğü' : 'Brand visibility on cups',
                        app()->getLocale()==='tr' ? 'Etkin kampanya ve zincir desteği' : 'Campaign and chain support',
                        app()->getLocale()==='tr' ? 'Paketli servis ile uyumlu' : 'Compatible with takeaway service',
                    ],
                    5 => [
                        app()->getLocale()==='tr' ? 'Private label ambalaj seçenekleri' : 'Private label packaging options',
                        app()->getLocale()==='tr' ? 'Restoran ve otel segmentine uygun gramaj' : 'Grammage options for restaurant and hotel segments',
                        app()->getLocale()==='tr' ? 'Hijyen odaklı tekli paketleme' : 'Hygiene-oriented single sachet format',
                    ],
                    6 => [
                        app()->getLocale()==='tr' ? 'Logo baskılı stick ambalaj' : 'Logo printed stick packaging',
                        app()->getLocale()==='tr' ? 'Cross-sell için ürün seti modelleme' : 'Cross-sell focused serving bundle planning',
                        app()->getLocale()==='tr' ? 'Etkinlik ve zincir şube uyumu' : 'Suitable for events and chain branches',
                    ],
                ];
            @endphp
            @if($t)
                <article class="rounded-xl border border-slate-200 bg-white p-6">
                    <img loading="lazy" src="{{ asset($serviceImage) }}" alt="{{ $t->title }}" class="mb-4 h-44 w-full rounded-lg object-cover">
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
        <h2 class="text-xl font-semibold text-slate-900">{{ app()->getLocale()==='tr' ? 'Nasıl Çalışıyoruz?' : 'How We Work' }}</h2>
        <div class="mt-4 grid gap-3 md:grid-cols-4 text-sm">
            <div class="rounded bg-white p-3">1. {{ app()->getLocale()==='tr' ? 'İhtiyaç analizi' : 'Requirement analysis' }}</div>
            <div class="rounded bg-white p-3">2. {{ app()->getLocale()==='tr' ? 'Numune / onay' : 'Sampling / approval' }}</div>
            <div class="rounded bg-white p-3">3. {{ app()->getLocale()==='tr' ? 'Üretim planlama' : 'Production planning' }}</div>
            <div class="rounded bg-white p-3">4. {{ app()->getLocale()==='tr' ? 'Sevkiyat ve takip' : 'Shipment and follow-up' }}</div>
        </div>
    </div>
</section>
@endsection
